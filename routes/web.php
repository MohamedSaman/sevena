<?php

use Illuminate\Http\Request;
use App\Livewire\CustomLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Staff\StaffDashboard;
use App\Livewire\Staff\EmployeeManagement;
use App\Livewire\Staff\SalaryManagement;
use App\Livewire\Staff\ProductionManagement;
use App\Livewire\Staff\SettingManagement;
use App\Livewire\Staff\LoanManagement;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', CustomLogin::class)->name('welcome')->middleware('guest');

// Custom logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Routes that require authentication
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // !! Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');


    });

   
    //!! Staff routes
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', StaffDashboard::class)->name('dashboard');
        Route::get('/employee-management', EmployeeManagement::class)->name('employee-management');
        Route::get('/salary-management', SalaryManagement::class)->name('salary-management');
        Route::get('/production-management', ProductionManagement::class)->name('production-management');
        Route::get('/setting-management', SettingManagement::class)->name('setting-management');
        Route::get('/staff/loan-management', LoanManagement::class)->name('loan-management');

        
    });


    // !! Export routes (accessible to authenticated users)

 
});
