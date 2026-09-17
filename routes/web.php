<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────────────────────
Route::get('/', [ResearchController::class, 'index'])->name('home');
Route::get('/about', function () { return view('pages.about'); })->name('about');
Route::get('/faq', function () { return view('pages.faq'); })->name('faq');
Route::get('/contact', function () { return view('pages.contact'); })->name('contact');
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);
    \App\Models\Message::create([
        'name'    => $request->name,
        'email'   => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);
    return back()->with('contact_success', true);
})->name('contact.send');
Route::get('/research/{research}', [ResearchController::class, 'show'])->name('research.show');
Route::get('/research/{research}/download', [ResearchController::class, 'download'])->name('research.download');
Route::get('/department/{department}', [ResearchController::class, 'byDepartment'])->name('research.department')->where('department', '[^/]+');
Route::get('/department/{department}/{course}', [ResearchController::class, 'byCourse'])->name('research.course')->where(['department' => '[^/]+', 'course' => '[^/]+']);

// ── Auth routes ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Forgot / Reset Password ────────────────────────────────────────────────
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = \Illuminate\Support\Facades\Password::sendResetLink($request->only('email'));
    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with('status', 'A password reset link has been sent to your email.')
        : back()->withErrors(['email' => 'We could not find an account with that email address.']);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);
    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (\App\Models\User $user, string $password) {
            $user->forceFill(['password' => \Illuminate\Support\Facades\Hash::make($password)])->save();
        }
    );
    return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset successfully! You can now login.')
        : back()->withErrors(['email' => 'Invalid or expired reset link. Please try again.']);
})->middleware('guest')->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated user routes ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/submit', [ResearchController::class, 'submitForm'])->name('research.submit');
    Route::post('/submit', [ResearchController::class, 'store'])->name('research.store');
    Route::get('/my-submissions', [ResearchController::class, 'mySubmissions'])->name('research.my-submissions');
    Route::get('/pinned', [ResearchController::class, 'pinned'])->name('research.pinned');
    Route::post('/pin/{research}', [ResearchController::class, 'pinToggle'])->name('research.pin');

     // ✅ ADD MO ITO
    Route::get('/research/{research}/protected-view', [ResearchController::class, 'protectedView'])->name('research.protectedView');
    Route::get('/research/{research}/stream-pdf', [ResearchController::class, 'streamPdf'])->name('research.streamPdf');
    Route::get('/research/{research}/admin-view', [ResearchController::class, 'adminViewFile'])->name('research.adminViewFile');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::get('/profile/photo/remove', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');

    Route::get('/research/{research}/page-image/{page}', [ResearchController::class, 'streamPageImage'])
    ->name('research.streamPageImage');
});

// ── Admin routes ───────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Research management
    Route::get('/researches', [AdminController::class, 'researches'])->name('researches');
    Route::get('/researches/{research}', [AdminController::class, 'showResearch'])->name('research.show');
    Route::get('/researches/{research}/view-file', [ResearchController::class, 'adminViewFile'])->name('research.view-file');
    Route::get('/researches/{research}/download-file', [ResearchController::class, 'adminDownloadFile'])->name('research.download-file');
    Route::post('/researches/{research}/approve', [AdminController::class, 'approveResearch'])->name('research.approve');
    Route::post('/researches/{research}/reject', [AdminController::class, 'rejectResearch'])->name('research.reject');
    Route::delete('/researches/{research}', [AdminController::class, 'deleteResearch'])->name('research.delete');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('user.show');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('user.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('user.delete');

    // Pending researcher approvals
    Route::get('/pending-researchers', [AdminController::class, 'pendingResearchers'])->name('pending-researchers');
    Route::patch('/pending-researchers/{user}/approve', [AdminController::class, 'approveResearcher'])->name('approve-researcher');
    Route::delete('/pending-researchers/{user}/reject', [AdminController::class, 'rejectResearcher'])->name('reject-researcher');

    // Messages
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('message.show');
    Route::delete('/messages/{message}', [AdminController::class, 'deleteMessage'])->name('message.delete');
    Route::post('/messages/mark-all-read', [AdminController::class, 'markAllRead'])->name('messages.mark-read');

    // Add research
    Route::get('/add-research', [AdminController::class, 'addResearch'])->name('add-research');
    Route::post('/add-research', [AdminController::class, 'storeResearch'])->name('store-research');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Admin management (legacy)
    Route::get('/create-admin', [AdminController::class, 'createAdmin'])->name('create-admin');
    Route::post('/create-admin', [AdminController::class, 'storeAdmin'])->name('store-admin');

    // Researcher accounts & graduation
    Route::get('/researcher-accounts', [AdminController::class, 'researcherAccounts'])->name('researcher-accounts');
    Route::get('/graduated-researchers', [AdminController::class, 'graduatedResearchers'])->name('graduated-researchers');
    Route::post('/users/{user}/restore-researcher', [AdminController::class, 'restoreResearcher'])->name('users.restore-researcher');

     // Researcher status management
    Route::post('/users/{user}/mark-irregular', [AdminController::class, 'markIrregular'])
        ->name('users.mark-irregular');
    Route::post('/users/{user}/mark-dropped-out', [AdminController::class, 'markDroppedOut'])
        ->name('users.mark-dropped-out');
    Route::post('/users/{user}/restore-to-active', [AdminController::class, 'restoreToActive'])
        ->name('users.restore-to-active');
});

// ── Super Admin routes ─────────────────────────────────────────────────────
    Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'admin', 'super-admin'])->group(function () {
    Route::get('/admins', [SuperAdminController::class, 'admins'])->name('admins');
    Route::get('/admins/create', [SuperAdminController::class, 'createAdmin'])->name('create-admin');
    Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('store-admin');
    Route::post('/admins/{user}/toggle', [SuperAdminController::class, 'toggleAdmin'])->name('toggle-admin');
    Route::delete('/admins/{user}', [SuperAdminController::class, 'deleteAdmin'])->name('delete-admin');
    Route::get('/trash', [SuperAdminController::class, 'trash'])->name('trash');
    Route::patch('/trash/{id}/restore', [SuperAdminController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{id}', [SuperAdminController::class, 'forceDelete'])->name('trash.destroy');
    Route::delete('/trash/empty', [SuperAdminController::class, 'emptyTrash'])->name('trash.empty');

});

// ── Owner routes ─────────────────────────────────────────────────────
Route::get('/research/{research}/view-file', [ResearchController::class, 'viewFile'])
    ->name('research.view-file')
    ->middleware('auth');

 