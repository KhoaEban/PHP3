<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Trang chủ
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Trang chủ', route('user.index'));
});

// Sản phẩm
Breadcrumbs::for('products', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Sản phẩm', route('user.products'));
});

// Danh mục sản phẩm
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('products');
    $trail->push($category->name, route('user.products', ['category' => $category->slug]));
});
