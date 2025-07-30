<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CategorieController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BoutiqueVueController;
use App\Http\Controllers\CommandebackController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;


Route::get('/', [SiteController::class, 'accueil']);

Route::get('/dashboard', function () {
    return view('/auth/dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');

Route::get('/shop', [BoutiqueController::class, 'index'])->middleware(['auth', 'admin'])->name('shop');
Route::post('/shop/create', [BoutiqueController::class, 'store'])->middleware(['auth', 'verified'])->name('boutiques.store');
Route::get('/shop/{shop}/edit', [BoutiqueController::class, 'edit'])->middleware(['auth', 'verified'])->name('boutiques.edit');
Route::put('/shop/{id}', [BoutiqueController::class, 'update'])->middleware(['auth', 'verified'])->name('boutiques.update');
Route::delete('/shop/{id}', [BoutiqueController::class, 'destroy'])->middleware(['auth', 'verified'])->name('boutiques.destroy');

Route::get('/commandes', [CommandebackController::class, 'index'])->middleware(['auth', 'verified'])->name('commande.index');
Route::post('/commandes/create', [CommandebackController::class, 'store'])->middleware(['auth', 'verified'])->name('commande.store');
Route::get('/commandes/export', [\App\Http\Controllers\CommandebackController::class, 'export'])->name('commande.export');




Route::resource('categories', CategorieController::class)
->middleware(['auth', 'verified'])
->only(['index', 'store', 'update', 'destroy']);


Route::resource('produits', ProduitController::class)->except(['show', 'create', 'edit']);


Route::get('/categorie', function () {
    return view('/auth/categorie/categorie');
})->middleware(['auth', 'verified'])->name('categorie');
Route::get('/produit', function () {
    return view('/auth/product/produit');
})->middleware(['auth', 'verified'])->name('produit');
Route::get('/commande', function () {
    return view('/auth/commande/commande');
})->middleware(['auth', 'verified'])->name('commande');
Route::get('/rapport', function () {
    return view('rapport');
})->middleware(['auth', 'verified'])->name('rapport');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::get('/boutiques/{boutique}', [BoutiqueVueController::class, 'show'])->name('boutiques.details');
Route::get('/boutiques', [BoutiqueVueController::class, 'indexAll'])->name('boutiques.all');



Route::get('/order', [CommandeController::class, 'index'])->name('order.index');
Route::post('/commande/creer', [CommandeController::class, 'store'])->name('order.store');
Route::get('/category/{id}', [CategorieController::class, 'show'])->name('category.show');





require __DIR__.'/auth.php';
