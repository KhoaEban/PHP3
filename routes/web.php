<?php

use Illuminate\Support\Facades\Route;
// Route Auth
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// Route cho admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
// Route cho user
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProductControllerUser;

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->name('admin.dashboard');

Route::get('/', function () {
    return view('user.index');
})->name('user.index');

//Admin
Route::middleware(['check.role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $title = 'Trang quản trị';
        return view('admin.dashboard', compact('title'));
    })->name('admin.dashboard');

    //Route quản lý sản phẩm trong Admin
    Route::prefix('admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/update/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/delete/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Route quản lý danh mục trong Admin
    Route::prefix('admin')->group(function () {
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');

        Route::get('/category/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/update/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

        Route::get('/category/create-sub/{parent_id}', [CategoryController::class, 'createSubcategory'])->name('category.create.sub');
        Route::put('/category/remove-parent/{id}', [CategoryController::class, 'removeParent'])->name('category.remove_parent');
        Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
    });

    // Route quản lý thương hiệu trong Admin
    Route::prefix('admin')->group(function () {
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/update/{id}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });
});

// Route cho user
Route::prefix('user')->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('user.index');

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductControllerUser::class, 'index'])->name('user.products');
        Route::get('/{slug}', [ProductControllerUser::class, 'show'])->name('user.products.show');
    });



    // About lab1
    Route::get('/about', function () {
        // Render dữ liệu cho view
        $data = [
            'name' => 'Y Khoa Êban',
            'age' => 20,
            'address' => 'Buôn Pôc A, CưMgar, Đắk Lắk',
            'phone' => '0389195765',
            'email' => 'khoaebanypk03641@gmail.com',
            'skills' => ['HTML', 'CSS', 'JS', 'PHP'],
            'profile' => 'full stack developer',
            'about' => 'Curabitur không phải là thời điểm tốt nhất cho những người chơi đã đọc sách. Đó là một kho lưu trữ, một lớp tài chính, đồng thời là một hãng hàng không. Đó là một khối khôn ngoan, thung lũng thậm chí không dành cho trẻ em, nó chỉ cần thiết. Không có nhà phát triển hãng hàng không.
            Mauris tâng bốc giới thượng lưu, cần sự tin cậy nibh pulvinar a. Vivamus suscepti tortor eget felis porttitor volutpat. Công trình, cái sân quan trọng hơn yếu tố phương tiện nhưng lại quan trọng đối với gia chủ. phà trên biển
            Không có nhà phát triển hãng hàng không. Mọi người đều muốn ngoại trừ giá ở mức lacinia cho yếu tố đó. Không có nhà phát triển hãng hàng không. Mauris tâng bốc giới thượng lưu, cần sự tin cậy nibh pulvinar a.'
        ];

        return view('user.about', $data);
    })->name('about');
});


// Xử lý đăng ký & đăng nhập
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/email', [AuthController::class, 'sendMail'])->name('send-mail')->middleware('auth');
// Forgot password & Reset password
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
// Google login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Hiển thị form đăng ký & đăng nhập
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Hiển thị form gửi mail nhập lại mật khẩu
Route::get('/email', function () {
    return view('auth.passwords.email');
})->name('send-mail.form');

Route::get('/password/reset', function () {
    return view('auth.passwords.reset');
})->name('password.reset.form');

// Error 404
Route::get('/404', function () {
    return view('errors');
})->name('404');
