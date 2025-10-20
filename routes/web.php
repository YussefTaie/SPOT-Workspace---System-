    <?php

    use App\Http\Controllers\SessionController;
    use App\Http\Controllers\MenuItemController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\StaffController;

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\GuestController;
    use Illuminate\Support\Facades\Route;



    Route::get('/', function () {return view('landingpage');})->name('landing');


    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
    
    Route::get('/guests/create', [GuestController::class, 'create'])->name('guests.create');

    Route::get('/login', [GuestController::class, 'showLoginForm'])->name('guests.login');

    Route::post('/login', [GuestController::class, 'login'])->name('guests.login.submit');

    Route::get('/scan', [SessionController::class, 'scan'])->name('sessions.scan');

    

    Route::get('/scanner', function () {return view('gest.scanner');})->name('scanner');


    Route::get('/admin/sessions-durations', [App\Http\Controllers\AdminController::class, 'getDurations'])->name('admin.sessions.durations');


    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/sessions-data', [AdminController::class, 'getSessionsData']);

    Route::get('/admin/sessions-data', [App\Http\Controllers\AdminController::class, 'fetchSessionsData'])->name('admin.sessions.data');

    Route::get('/check/{id}', [SessionController::class, 'check'])->name('sessions.check');

    Route::post('/sessions/{session}/end', [SessionController::class, 'endSession'])->name('sessions.end');

    Route::get('/profile/{guest}', [GuestController::class, 'profile'])->name('profile.user');





// Route::get('/admin/{admin}',[AdminController::class,'admin'])->name('admin.dashboard');


// Guests CRUD
// Route::resource('guests', GuestController::class);

// Sessions CRUD
Route::resource('sessions', SessionController::class);

// Menu CRUD
Route::resource('menu', MenuItemController::class);

// Orders CRUD
Route::resource('orders', OrderController::class);

// Staff CRUD
Route::resource('staff', StaffController::class);


Route::get('/order-now/{session}', [OrderController::class, 'create'])->name('orders.create');
Route::post('/order-now', [OrderController::class, 'store'])->name('orders.store');


Route::get('/test-time', function () {
    return now();
});
