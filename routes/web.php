<?php

use App\Livewire\Brands;
use App\Livewire\Products;
use App\Livewire\Categories;

use Illuminate\Support\Facades\Route;

Route::get('/', Products::class)->name('products');
Route::get('/brands', Brands::class)->name('brands');
Route::get('/categories', Categories::class)->name('categories');
