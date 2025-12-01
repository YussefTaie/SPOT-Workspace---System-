    <?php

    use App\Http\Controllers\SessionController;
    use App\Http\Controllers\MenuItemController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\StaffController;
    use App\Http\Controllers\BaristaController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\GuestController;
    use App\Http\Controllers\StaffAuthController;
    use App\Http\Controllers\OrderSyncController;
    use App\Http\Controllers\Admin\MenuItemController as AdminMenuItemController;
    
    use Illuminate\Support\Facades\Route;



    Route::get('/', function () {return view('landingpage');})->name('landing');


    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
    
    Route::get('/guests/create', [GuestController::class, 'create'])->name('guests.create');

    Route::get('/login', [GuestController::class, 'showLoginForm'])->name('guests.login');

    Route::post('/login', [GuestController::class, 'login'])->name('guests.login.submit');

    Route::get('/scan', [SessionController::class, 'scan'])->name('sessions.scan');

    

    Route::get('/scanner', function () {return view('gest.scanner');})->name('scanner');


    Route::get('/admin/sessions-durations', [App\Http\Controllers\AdminController::class, 'getDurations'])->name('admin.sessions.durations');


    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/sessions-data', [AdminController::class, 'getSessionsData']);

    Route::get('/admin/sessions-data', [App\Http\Controllers\AdminController::class, 'fetchSessionsData'])->name('admin.sessions.data');

    Route::get('/check/{id}', [SessionController::class, 'check'])->name('sessions.check');

    Route::post('/sessions/{session}/end', [SessionController::class, 'endSession'])->name('sessions.end');

    Route::get('/profile/{guest}', [GuestController::class, 'profile'])->name('profile.user');


    //Barista
    // Route::get('/barista/dashboard', [BaristaController::class, 'Baristadashboard'])->name('barista.dashboard');

    // Order actions used by barista
    Route::post('/orders/{order}/accept', [\App\Http\Controllers\OrderController::class, 'accept'])->name('orders.accept');

    Route::post('/orders/{order}/mark-done', [\App\Http\Controllers\OrderController::class, 'markDone'])->name('orders.markDone');
    
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');


    // Menu CRUD
    Route::get('/barista/menu', [MenuItemController::class, 'menu'])->name('barista.menu');

    // صفحة المستخدم العامة للـ menu
    Route::get('/menu', [MenuItemController::class, 'index'])->name('menu.index');

    Route::get('/menu/{guest}', [App\Http\Controllers\MenuItemController::class, 'guestMenu'])->name('menu.guest');

    // show create form
    Route::get('/menu/create', [App\Http\Controllers\Admin\MenuItemController::class, 'create'])->name('admin.menu.create');

    // show edit form
    Route::get('/menu/{menuItem}/edit', [App\Http\Controllers\Admin\MenuItemController::class, 'edit'])->name('admin.menu.edit');






    // Staff auth
    Route::get('/staff/login', [StaffAuthController::class, 'showLogin'])->name('staff.login');
    Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
    Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

    // Barista routes (محميين بميدل وير)
    Route::prefix('barista')->middleware(['auth:barista', 'is_barista'])->group(function () {


    Route::get('/dashboard', [\App\Http\Controllers\BaristaController::class, 'Baristadashboard'])->name('barista.dashboard');   
    // عرض المينيو للبارستا - نستخدم BaristaController عشان نمرّر guest وهمي
    Route::get('/menu', [BaristaController::class, 'menu'])->name('barista.menu');

    // Past orders للبارستا (اختياري ولكن مفيد)
    Route::get('/orders/past', [BaristaController::class, 'pastOrders'])->name('barista.orders.past');  

    Route::get('/barista/orders/partials', [\App\Http\Controllers\Barista\OrderSyncController::class, 'partials'])
     ->name('barista.orders.partials');

     Route::get('/orders/partials', [\App\Http\Controllers\Barista\OrderSyncController::class, 'partials'])
     ->name('barista.orders.partials');



    // مثال: رووت صفحة منيو الباريستا (لو تستخدمها)
    // Route::get('/menu', [MenuItemController::class, 'menu'])->name('barista.menu');

    // إجراءات الأوردر الخاصة بالباريستا
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/mark-done', [OrderController::class, 'markDone'])->name('orders.markDone');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
    


    Route::prefix('admin')->middleware(['auth:admin', 'is_admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
        // إدارة المنيو بواسطة الأدمن
        Route::get('/menu', [App\Http\Controllers\Admin\MenuItemController::class, 'index'])->name('admin.menu.index');
        Route::get('/menu/create', [App\Http\Controllers\Admin\MenuItemController::class, 'create'])->name('admin.menu.create');
        Route::post('/menu', [App\Http\Controllers\Admin\MenuItemController::class, 'store'])->name('admin.menu.store');
        Route::get('/menu/{menuItem}/edit', [App\Http\Controllers\Admin\MenuItemController::class, 'edit'])->name('admin.menu.edit');
        Route::put('/menu/{menuItem}', [App\Http\Controllers\Admin\MenuItemController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{menuItem}', [App\Http\Controllers\Admin\MenuItemController::class, 'destroy'])->name('admin.menu.destroy');
    });

    // HOST ROUTES
    Route::prefix('host')->middleware(['auth:host', 'is_host'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'host'])->name('host.dashboard');
    });
    



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
