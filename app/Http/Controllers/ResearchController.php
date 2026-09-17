<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;


class ResearchController extends Controller
{
    // ─────────────────────────────────────────────
    // PUBLIC INDEX / HOME
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Research::approved()->with('user');

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($field = $request->get('field')) {
            if ($field === 'title') {
                $query->where('title', 'like', '%' . $request->search . '%');
            } elseif ($field === 'author') {
                $query->where('author_name', 'like', '%' . $request->search . '%');
            } elseif ($field === 'abstract') {
                $query->where('abstract', 'like', '%' . $request->search . '%');
            }
        }

        if ($dept = $request->get('department')) {
            $query->where('department', $dept);
        }

        if ($yearFrom = $request->get('year_from')) {
            $query->where('year_published', '>=', $yearFrom);
        }

        if ($yearTo = $request->get('year_to')) {
            $query->where('year_published', '<=', $yearTo);
        }

        $researches  = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $departments = Research::approved()->distinct()->pluck('department')->sort()->values();
        $featured    = Research::approved()->orderBy('view_count', 'desc')->take(6)->get();

        return view('home', compact('researches', 'departments', 'featured'));
    }

    // ─────────────────────────────────────────────
    // SHOW RESEARCH DETAIL
    // ─────────────────────────────────────────────
    public function show(Research $research)
    {
        if ($research->status !== 'approved') {
            if (!auth()->check() || auth()->id() !== $research->user_id) {
                abort(404);
            }
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        $research->increment('view_count');
        return view('research.show', compact('research'));
    }

    // ─────────────────────────────────────────────
    // SUBMIT FORM
    // ─────────────────────────────────────────────
    public function submitForm()
    {
        if (auth()->user()->isGraduated()) {
            return redirect()->route('home')
                ->with('error', 'Graduated researchers cannot submit.');
        }

        if (!auth()->user()->isAdmin() && empty(auth()->user()->student_id)) {
            return redirect()->route('home')
                ->with('error', 'Only valid students/faculty can submit.');
        }

        return view('research.submit');
    }

    // ─────────────────────────────────────────────
    // STORE / SAVE SUBMISSION
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        if (auth()->user()->isGraduated()) {
            return redirect()->back()->with('error', 'Graduated users cannot submit.');
        }

        if (!auth()->user()->isAdmin() && empty(auth()->user()->student_id)) {
            return back()->with('error', 'Only valid users can submit.');
        }

        $data = $request->validate([
            'title'          => ['required', 'string', 'min:10', 'max:500'],
            'type'           => 'required',
            'author_name'    => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'course'         => 'required|string|max:255',
            'year_published' => 'required|integer|min:2021|max:' . (date('Y') + 1),
            'keywords'       => 'nullable|string|max:500',
            'abstract'       => 'required|string',
            'file'           => 'required|file|mimes:pdf|max:30720',
        ]);

        $file     = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('researches', $fileName, 'public');

        Research::create([
            'title'          => $data['title'],
            'type'           => $data['type'],
            'author_name'    => $data['author_name'],
            'user_id'        => Auth::id(),
            'department'     => $data['department'],
            'course'         => $data['course'],
            'year_published' => $data['year_published'],
            'keywords'       => $data['keywords'],
            'abstract'       => $data['abstract'],
            'file_path'      => $filePath,
            'file_name'      => $file->getClientOriginalName(),
            'status'         => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Research submitted successfully!');
    }

    // ─────────────────────────────────────────────
    // DOWNLOAD — ADMIN ONLY
    // ─────────────────────────────────────────────
    public function download(Research $research)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Login required.');
        }

        if (!Storage::disk('public')->exists($research->file_path)) {
            abort(404);
        }

        // ✅ Admin only can download
        if ($user->isAdmin()) {
            $research->increment('download_count');
            $path = Storage::disk('public')->path($research->file_path);
            return response()->download($path, $research->file_name);
        }

        // ❌ Block everyone else
        abort(403, 'Download is restricted to administrators only.');
    }

    // ─────────────────────────────────────────────
    // VIEW FILE — ROUTES TO CORRECT VIEWER
    // ─────────────────────────────────────────────
    public function viewFile(Research $research)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Login required.');
        }

        if ($research->status !== 'approved') {
            if (!$user->isAdmin() && auth()->id() !== $research->user_id) {
                abort(404);
            }
        }

        if (!Storage::disk('public')->exists($research->file_path)) {
            abort(404);
        }

        // ✅ Admins get full direct PDF (with toolbar, can print/download)
        if ($user->isAdmin()) {
            $path = Storage::disk('public')->path($research->file_path);
            return response()->file($path, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $research->file_name . '"',
            ]);
        }

        // 🔒 Students & Researchers → protected viewer (no print, no download)
        return redirect()->route('research.protectedView', $research->id);
    }

    // ─────────────────────────────────────────────
    // PROTECTED VIEWER PAGE (students & researchers)
    // ─────────────────────────────────────────────
    public function protectedView(Research $research)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Login required.');
        }

        if ($research->status !== 'approved') {
            if (!$user->isAdmin() && auth()->id() !== $research->user_id) {
                abort(404);
            }
        }

        // Generate a short-lived signed URL so the iframe src cannot be hotlinked
        $signedUrl = URL::temporarySignedRoute(
            'research.streamPdf',
            now()->addMinutes(30),
            ['research' => $research->id]
        );

        return view('research.protected-viewer', compact('research', 'signedUrl'));
    }

    // ─────────────────────────────────────────────
    // STREAM PDF — SIGNED URL (anti-hotlink, anti-download headers)
    // ✅ FIX: Removed auth() check inside this method.
    // The signed URL is already the security layer — it's time-limited and
    // tamper-proof, generated only for authenticated users in protectedView().
    // Adding auth() here caused iframes to show blank because browsers
    // sometimes don't forward the Laravel session cookie inside iframes.
    // ─────────────────────────────────────────────
    public function streamPdf(Request $request, Research $research)
    {
        // Reject expired or tampered signed URLs
        if (!$request->hasValidSignature()) {
            abort(403, 'This link has expired or is invalid. Please go back and try again.');
        }

        if ($research->status !== 'approved') {
            abort(404);
        }

        if (!Storage::disk('public')->exists($research->file_path)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($research->file_path);

        return response()->file($path, [
            'Content-Type'           => 'application/pdf',
            'Content-Disposition'    => 'inline; filename="' . $research->file_name . '"',
            'Cache-Control'          => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'                 => 'no-cache',
            'Expires'                => '0',
            'X-Frame-Options'        => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // ─────────────────────────────────────────────
    // ADMIN VIEW FILE (direct, full toolbar)
    // ─────────────────────────────────────────────
    public function adminViewFile(Research $research)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Admins only.');
        }

        $path = Storage::disk('public')->path($research->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $research->file_name . '"',
        ]);
    }

    // ─────────────────────────────────────────────
    // MY SUBMISSIONS
    // ─────────────────────────────────────────────
    public function mySubmissions()
    {
        $researches = Research::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('research.my-submissions', compact('researches'));
    }

    // ─────────────────────────────────────────────
    // PIN / UNPIN TOGGLE
    // ─────────────────────────────────────────────
    public function pinToggle(Research $research)
    {
        $user = Auth::user();

        if ($user->pinnedResearches()->where('research_id', $research->id)->exists()) {
            $user->pinnedResearches()->detach($research->id);
            $pinned = false;
        } else {
            $user->pinnedResearches()->attach($research->id);
            $pinned = true;
        }

        return response()->json(['pinned' => $pinned]);
    }

    // ─────────────────────────────────────────────
    // PINNED RESEARCHES
    // ─────────────────────────────────────────────
    public function pinned()
    {
        $researches = Auth::user()->pinnedResearches()->paginate(10);
        return view('research.pinned', compact('researches'));
    }

    // ─────────────────────────────────────────────
    // BY DEPARTMENT
    // ─────────────────────────────────────────────
    public function byDepartment($department)
    {
        $department = rawurldecode($department);

        $researches = Research::approved()
            ->where('department', $department)
            ->paginate(12);

        return view('research.department', compact('researches', 'department'));
    }

    // ─────────────────────────────────────────────
    // BY COURSE
    // ─────────────────────────────────────────────
    public function byCourse($department, $course)
    {
        $department = rawurldecode($department);
        $course     = rawurldecode($course);

        $researches = Research::approved()
            ->where('department', $department)
            ->where('course', $course)
            ->paginate(12);

        return view('research.department', compact('researches', 'department', 'course'));
    }
}