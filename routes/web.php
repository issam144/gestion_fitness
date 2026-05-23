<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AbonnementController;
use App\Models\TypeAbonnement; 

/* ------------------------------------------
   --- 0. PUBLIC & AUTH ROUTES ---
   ------------------------------------------ */
Route::get('/', function () { 
    return view('welcome', ['plans' => TypeAbonnement::all()]); 
})->name('welcome');

Auth::routes();

Route::get('/home', function() {
    $user = Auth::user();
    if (!$user) return redirect('/login');
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'coach') return redirect()->route('coach.dashboard');
    
    $freshUser = $user->fresh();
    if (!$freshUser->expired_at || $freshUser->expired_at->isPast()) {
        return redirect()->route('client.plans');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'approved'])->name('home');

/* ------------------------------------------
   --- 1. ADMIN SPACE ---
   ------------------------------------------ */
Route::middleware(['auth', 'role:admin', 'approved'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
    
    // إدارة المستخدمين
    Route::get('/pending-users', [AdminController::class, 'pendingUsers'])->name('pending');
    Route::post('/approve/{id}', [AdminController::class, 'approveUser'])->name('approve');
    Route::delete('/reject/{id}', [AdminController::class, 'destroy'])->name('reject');
    Route::get('/coach-reports', [AdminController::class, 'coachReports'])->name('coach.reports');

    // السكينر والحضور
    Route::get('/scanner', [AttendanceController::class, 'scanner'])->name('scanner');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');

    // الموارد
    Route::resource('coachs', CoachController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::resource('type-abonnements', \App\Http\Controllers\TypeAbonnementController::class);
    Route::resource('type-seances', \App\Http\Controllers\TypeSeanceController::class);
    Route::resource('seances', SeanceController::class);
    Route::resource('abonnements', \App\Http\Controllers\AbonnementController::class);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
});

/* ------------------------------------------
   --- 2. COACH SPACE ---
   ------------------------------------------ */
Route::middleware(['auth', 'role:coach', 'approved'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');
    Route::get('/seances', [CoachController::class, 'seances'])->name('seances');
    Route::get('/mes-membres', [CoachController::class, 'members'])->name('members');
    
    // الحضور والغياب
    Route::get('/seance/{id}/members', [CoachController::class, 'viewSessionMembers'])->name('seance.members');
    Route::post('/mark-member-attendance', [CoachController::class, 'markMemberAttendance'])->name('markMemberAttendance');
    Route::post('/seance/{id}/mark-present', [CoachController::class, 'markPresent'])->name('seance.present');
    Route::post('/seance/{id}/mark-absent', [CoachController::class, 'markAbsent'])->name('seance.absent');
    
    Route::get('/presences-hub', [CoachController::class, 'presencesHub'])->name('presences_hub');
    Route::post('/presence-submit/{id}', [CoachController::class, 'updatePresence'])->name('updatePresence');
    Route::get('/mon-profil', [CoachController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
});

/* ------------------------------------------
   --- 3. CLIENT SPACE ---
   ------------------------------------------ */
Route::prefix('client')->name('client.')->group(function () {
    
    Route::middleware(['auth', 'approved'])->group(function () {
        Route::get('/plans', [DashboardController::class, 'showPlans'])->name('plans');
        Route::get('/checkout/{plan_id}', [DashboardController::class, 'checkout'])->name('checkout');
        Route::post('/process-payment', [DashboardController::class, 'processPayment'])->name('process.payment');
        Route::post('/checkout/custom', [DashboardController::class, 'checkoutCustom'])->name('checkout.custom');
        Route::get('/profile', [DashboardController::class, 'clientProfile'])->name('profile');
        Route::get('/profile', [DashboardController::class, 'clientProfile'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    });

    Route::middleware(['auth', 'role:client', 'approved'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'clientIndex'])->name('dashboard');
        Route::get('/nos-sports', [DashboardController::class, 'allSports'])->name('sports');
        Route::get('/sport/{id}/coachs', [DashboardController::class, 'coachsBySport'])->name('sport.coachs');
        Route::get('/planning-global', [DashboardController::class, 'globalPlanning'])->name('planning');
        Route::get('/historique', [DashboardController::class, 'clientHistory'])->name('history');
        Route::get('/download-receipt', [DashboardController::class, 'downloadReceipt'])->name('download.receipt');
        Route::post('/join-commander/{id}', [DashboardController::class, 'joinCoach'])->name('join.coach');
        Route::post('/rate-coach/{id}', [DashboardController::class, 'rateCoach'])->name('rate.coach');
    });
});

/* ------------------------------------------
   --- 4. GENERAL PAGES ---
   ------------------------------------------ */
Route::get('/concept', function () { return view('pages.concept'); })->name('concept');
Route::get('/cardio', function () { return view('pages.cardio'); })->name('cardio');
Route::get('/burning-park', function () { return view('pages.burning'); })->name('burning');
Route::get('/cours-collectifs', function () { return view('pages.collectifs'); })->name('collectifs');

// Bedli 'preinscription.success' b 'auth.pending_approval'
Route::view('/preinscription-reussie', 'auth.pending_approval')->name('preinscription.reussie');