<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_researches' => Research::count(),
            'pending'          => Research::pending()->count(),
            'approved'         => Research::approved()->count(),
            'rejected'         => Research::where('status', 'rejected')->count(),
            'total_users'      => User::where('role', '!=', 'admin')->count(),
            'researchers'      => User::where('role', 'researcher')->count(),
            'total_views'      => Research::sum('view_count'),
            'total_downloads'  => Research::sum('download_count'),
        ];

        $pendingResearches = Research::pending()->with('user')->latest()->take(5)->get();
        $recentResearches  = Research::approved()->with('user')->latest()->take(5)->get();
        $topResearches     = Research::approved()->orderBy('view_count', 'desc')->take(5)->get();

        $researchByDept = Research::approved()
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $researchByYear = Research::approved()
            ->selectRaw('year_published, count(*) as count')
            ->groupBy('year_published')
            ->orderBy('year_published')
            ->get();

        $allResearchers = User::where('role', 'researcher')->where('is_approved', true)->orderBy('name')->get();
        $allResearches  = Research::with('user')->latest()->get();
        $allUsers       = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('admin.dashboard', compact(
            'stats', 'pendingResearches', 'recentResearches',
            'topResearches', 'researchByDept', 'researchByYear',
            'allResearchers', 'allResearches', 'allUsers'
        ));
    }

    // ── Research Management ──────────────────────────────────────────────────

    public function researches(Request $request)
    {
        $query = Research::with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($dept = $request->get('department')) {
            $query->where('department', $dept);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        $researches  = $query->latest()->paginate(15)->withQueryString();
        $departments = Research::distinct()->pluck('department')->sort()->values();

        return view('admin.researches', compact('researches', 'departments'));
    }

    public function approveResearch(Research $research)
    {
        $research->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Research "' . $research->title . '" has been approved.');
    }

    public function rejectResearch(Request $request, Research $research)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $research->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', 'Research has been rejected.');
    }

    public function deleteResearch(Research $research)
    {
        $research->delete();
        return back()->with('success', 'Research deleted successfully.');
    }

    public function showResearch(Research $research)
    {
        return view('admin.research-detail', compact('research'));
    }

    // ── User Management ──────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // Filter by member_type (student = has graduation_year, faculty = null)
        if ($memberType = $request->get('member_type')) {
            if ($memberType === 'student') {
                $query->where('role', 'researcher')->whereNotNull('graduation_year');
            } elseif ($memberType === 'faculty') {
                $query->where('role', 'researcher')->whereNull('graduation_year');
            }
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount('researches')->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        $researches = $user->researches()->latest()->paginate(10);
        return view('admin.user-detail', compact('user', 'researches'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$user->name} has been {$status}.");
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // ── Researcher Approval ──────────────────────────────────────────────────

    public function pendingResearchers(Request $request)
    {
        $query = User::where('role', 'researcher')->where('is_approved', false);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $pendingResearchers = $query->latest()->paginate(15)->withQueryString();
        return view('admin.pending-researchers', compact('pendingResearchers'));
    }

    public function approveResearcher(User $user)
    {
        $user->update(['is_approved' => true]);

        try {
            Mail::to($user->email)->send(new \App\Mail\ResearcherApproved($user));
        } catch (\Exception $e) {
            // Mail failed silently
        }

        return back()->with('success', $user->name . ' has been approved as a Researcher.');
    }

    public function rejectResearcher(User $user)
    {
        $user->delete();
        return back()->with('success', 'Researcher account has been rejected and deleted.');
    }

    // ── Messages ─────────────────────────────────────────────────────────────

    public function messages(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query  = Message::latest();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        }

        $messages    = $query->paginate(15)->withQueryString();
        $unreadCount = Message::where('is_read', false)->count();

        return view('admin.messages', compact('messages', 'unreadCount', 'filter'));
    }

    public function showMessage(Message $message)
    {
        $message->update(['is_read' => true]);
        return view('admin.message-detail', compact('message'));
    }

    public function deleteMessage(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages')->with('success', 'Message deleted.');
    }

    public function markAllRead()
    {
        Message::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All messages marked as read.');
    }

    // ── Add Research (Admin) ─────────────────────────────────────────────────

    public function addResearch()
    {
        return view('admin.add-research');
    }

    public function storeResearch(Request $request)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'min:10', 'max:500'],
            'type'           => 'required|in:Thesis,Feasibility Study,Descriptive Research,Correlational Research,Quantitative Research,Capstone 1,Capstone 2,Applied Research,Qualitative Research,Mixed Methods Research,Action Research,Experimental Research',
            'author_name'    => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'course'         => 'required|string|max:255',
            'year_published' => 'required|integer|min:2021|max:' . (date('Y') + 1),
            'keywords'       => 'nullable|string|max:500',
            'abstract'       => 'required|string',
            'file'           => 'nullable|file|mimes:pdf,doc,docx|max:30720',
        ], [
            'title.regex' => 'Research title may only contain letters, numbers, and basic punctuation ( \' - : , . & / ).',
            'title.min'   => 'Research title must be at least 10 characters.',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('researches', $fileName, 'public');
            $fileName = $file->getClientOriginalName();
        }

        Research::create([
            'title'          => $data['title'],
            'type'           => $data['type'],
            'author_name'    => $data['author_name'],
            'user_id'        => Auth::id(),
            'department'     => $data['department'],
            'course'         => $data['course'],
            'year_published' => $data['year_published'],
            'keywords'       => $data['keywords'] ?? null,
            'abstract'       => $data['abstract'],
            'file_path'      => $filePath,
            'file_name'      => $fileName,
            'status'         => 'approved',
            'approved_by'    => Auth::id(),
            'approved_at'    => now(),
        ]);

        return redirect()->route('admin.researches')->with('success', 'Research added and approved successfully!');
    }

    // ── Admin Management ──────────────────────────────────────────────────────

    public function createAdmin()
    {
        return view('admin.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        return redirect()->route('admin.users')->with('success', 'Admin account created successfully.');
    }

    // ── Reports ───────────────────────────────────────────────────────────────

    public function reports(Request $request)
    {
        $departments = [
            'College of Accountancy and Business Education',
            'College of Computer Studies',
            'College of Criminal Justice Education',
            'College of Education',
            'College of Engineering',
            'College of Maritime Studies',
        ];

        $query = Research::approved();

        if ($dept = $request->get('department')) {
            $query->where('department', $dept);
        }
        if ($year = $request->get('year')) {
            $query->where('year_published', $year);
        }
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $researches = $query->orderBy('department')->orderBy('year_published', 'desc')->get();
        $years      = Research::approved()->distinct()->pluck('year_published')->sortDesc()->values();

        return view('admin.reports', compact('researches', 'departments', 'years'));
    }

    // ── Researcher Accounts & Graduation ─────────────────────────────────────

    public function researcherAccounts()
    {
        $currentYear = (int) date('Y');
        $researchers = User::where('role', 'researcher')
            ->where('is_approved', true)
            ->withCount('researches')
            ->latest()
            ->paginate(20);
        return view('admin.researcher-accounts', compact('researchers', 'currentYear'));
    }

    public function graduatedResearchers()
    {
        $graduated = User::where('role', 'researcher')
            ->where('is_approved', true)
            ->whereNotNull('graduated_at')
            ->withCount('researches')
            ->latest('graduated_at')
            ->paginate(15);
        return view('admin.graduated-researchers', compact('graduated'));
    }

    public function restoreResearcher(User $user, Request $request)
    {
        $request->validate([
            'year_level'      => 'required|integer|in:1,2,3,4',
            'course_duration' => 'required|integer|in:3,4',
        ]);

        $newGraduationYear = (int) date('Y') + ((int) $request->course_duration - (int) $request->year_level);

        $user->update([
            'year_level'      => $request->year_level,
            'course_duration' => $request->course_duration,
            'graduation_year' => $newGraduationYear,
            'graduated_at'    => null,
            'is_active'       => true,
        ]);

        return back()->with('success', $user->name . ' is now an active researcher again. New graduation year: ' . $newGraduationYear . '.');
    }

    public function markIrregular(User $user, Request $request)
    {
        $request->validate([
            'year_level'      => 'required|integer|in:1,2,3,4',
            'course_duration' => 'nullable|integer|in:3,4',
        ]);

        $courseDuration    = $request->course_duration ?? $user->course_duration ?? 4;
        $newGraduationYear = (int) date('Y') + ((int) $courseDuration - (int) $request->year_level);

        $user->update([
            'year_level'        => $request->year_level,
            'course_duration'   => $courseDuration,
            'graduation_year'   => $newGraduationYear,
            'researcher_status' => 'irregular',
            'graduated_at'      => null,
            'is_active'         => true,
        ]);

        return back()->with('success', $user->name . ' has been marked as Irregular. New graduation year: ' . $newGraduationYear . '.');
    }

    public function markDroppedOut(User $user)
    {
        $user->update([
            'researcher_status' => 'dropped_out',
            'is_active'         => true,
        ]);

        return back()->with('success', $user->name . ' has been marked as Dropped Out. Their research remains published.');
    }

    public function restoreToActive(User $user, Request $request)
    {
        $request->validate([
            'year_level'      => 'required|integer|in:1,2,3,4',
            'course_duration' => 'nullable|integer|in:3,4',
        ]);

        $courseDuration    = $request->course_duration ?? $user->course_duration ?? 4;
        $newGraduationYear = (int) date('Y') + ((int) $courseDuration - (int) $request->year_level);

        $user->update([
            'year_level'        => $request->year_level,
            'course_duration'   => $courseDuration,
            'graduation_year'   => $newGraduationYear,
            'researcher_status' => null,
            'graduated_at'      => null,
            'is_active'         => true,
        ]);

        return back()->with('success', $user->name . ' has been restored as an Active Researcher. New graduation year: ' . $newGraduationYear . '.');
    }
}