<?php

use App\Livewire\Home;
use App\Livewire\Categories;
use App\Livewire\Brands;
use App\Livewire\Products;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/brands', Brands::class)->name('brands');
Route::get('/products', Products::class)->name('products');
Route::get('/categories', Categories::class)->name('categories');
