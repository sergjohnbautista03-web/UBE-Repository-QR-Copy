<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Show Login ───────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    // ── Login ────────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Deactivated account
        if ($user && ! $user->is_active) {
            return back()
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the administrator.'])
                ->withInput();
        }

        // Researcher pending approval
        if ($user && $user->role === 'researcher' && ! $user->is_approved) {
            return back()
                ->withErrors(['email' => 'Your researcher account is pending admin approval. Please wait for confirmation.'])
                ->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
            }

            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    // ── Show Register ────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    // ── Register ─────────────────────────────────────────────────────────────

    public function register(Request $request)
    {
        $data = $request->validate([
            'firstname'          => ['required', 'regex:/^[a-zA-Z\s\-\.]+$/', 'max:255'],
            'lastname'           => ['required', 'regex:/^[a-zA-Z\s\-\.]+$/', 'max:255'],
            'middlename'         => ['nullable', 'regex:/^[a-zA-Z\s\-\.]+$/', 'max:255'],

            'email' => [
                'required', 'email', 'unique:users',
                function ($attribute, $value, $fail) {
                    $allowed = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'philcst.edu.ph', 'icloud.com'];
                    $domain  = strtolower(substr(strrchr($value, '@'), 1));
                    if (! in_array($domain, $allowed)) {
                        $fail('Please use a valid email domain (gmail.com, yahoo.com, outlook.com, etc.)');
                    }
                },
            ],

            'password'   => ['required', 'confirmed', Password::min(8)->numbers()->symbols()],
            'role'       => ['required', 'in:user,researcher'],
            'department' => ['nullable', 'string', 'max:255'],

            // PhilCST student (role = user)
            'philcst_student_id' => [
                'nullable',
                'string',
                'max:50',
                // Required when role is 'user'
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('role') === 'user' && empty($value)) {
                        $fail('Student ID is required for PhilCST students.');
                    }
                },
                // Must be unique — prevent duplicate PhilCST student accounts
                function ($attribute, $value, $fail) {
                    if ($value && User::where('student_id', $value)->where('role', 'user')->exists()) {
                        $fail('An account with this Student ID already exists.');
                    }
                },
            ],

            // Researcher type — student or faculty
            'member_type' => [
                'nullable',
                'in:student,faculty',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('role') === 'researcher' && empty($value)) {
                        $fail('Please select whether you are a student or faculty.');
                    }
                },
            ],

            // Researcher student fields
            'student_id' => [
                'nullable', 'string', 'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('role') === 'researcher'
                        && $request->input('member_type') === 'student'
                        && empty($value)) {
                        $fail('Student ID is required.');
                    }
                },
            ],
            'year_level' => [
                'nullable', 'integer', 'in:1,2,3,4',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('role') === 'researcher'
                        && $request->input('member_type') === 'student'
                        && empty($value)) {
                        $fail('Year level is required.');
                    }
                },
            ],

            // Researcher faculty fields
            'employee_id' => [
                'nullable', 'string', 'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('role') === 'researcher'
                        && $request->input('member_type') === 'faculty'
                        && empty($value)) {
                        $fail('Employee ID is required.');
                    }
                },
            ],

        ], [
            'firstname.regex'  => 'First name must contain letters only.',
            'lastname.regex'   => 'Last name must contain letters only.',
            'middlename.regex' => 'Middle name must contain letters only.',
            'password.*'       => 'Password must be at least 8 characters with one number and one special character (@#$%!).',
            'role.required'    => 'Please select an account type.',
            'role.in'          => 'Invalid account type selected.',
        ]);

        $role       = $data['role'];
        $memberType = $data['member_type'] ?? null;

        // ── Build student_id column value ────────────────────────────────────
        // role=user  → philcst_student_id
        // role=researcher + student  → student_id
        // role=researcher + faculty  → employee_id (stored in student_id column)
        $studentId = match (true) {
            $role === 'user'                                    => $data['philcst_student_id'] ?? null,
            $role === 'researcher' && $memberType === 'student' => $data['student_id'] ?? null,
            $role === 'researcher' && $memberType === 'faculty' => $data['employee_id'] ?? null,
            default                                             => null,
        };

        // ── Graduation year (students only) ──────────────────────────────────
        $yearLevel      = null;
        $courseDuration = null;
        $graduationYear = null;

        if ($role === 'researcher' && $memberType === 'student' && isset($data['year_level'])) {
            $yearLevel      = (int) $data['year_level'];
            $courseDuration = 4;
            $graduationYear = (int) date('Y') + (4 - $yearLevel);
        }

        // ── Approval logic ───────────────────────────────────────────────────
        // PhilCST students (user) → auto-approved, log in immediately
        // Researchers             → needs admin approval
        $isApproved = $role === 'user';

        // ── Full name ────────────────────────────────────────────────────────
        $fullName = trim(
            $data['firstname'] . ' ' .
            ($data['middlename'] ? $data['middlename'] . ' ' : '') .
            $data['lastname']
        );

        // ── Create user ──────────────────────────────────────────────────────
        $user = User::create([
            'name'            => $fullName,
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'role'            => $role,
            'department'      => ($role === 'researcher') ? ($data['department'] ?? null) : null,
            'student_id'      => $studentId,
            'year_level'      => $yearLevel,
            'course_duration' => $courseDuration,
            'graduation_year' => $graduationYear,
            'is_approved'     => $isApproved,
            'is_active'       => true,
        ]);

        // ── Redirect ─────────────────────────────────────────────────────────
        if ($role === 'researcher') {
            return redirect()->route('login')->with(
                'success',
                'Registration successful! Your researcher account is pending admin approval. You will be notified once approved.'
            );
        }

        // PhilCST student — log in right away
        Auth::login($user);
        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome, ' . $user->name . '!');
    }

    // ── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}