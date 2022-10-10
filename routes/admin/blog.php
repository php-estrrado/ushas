<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\BlogPostController;

Route::get('/admin/blog/categories', [BlogCategoryController::class, 'index'])->name('admin.blogcategories');
Route::get('/admin/blog/newcategory', [BlogCategoryController::class, 'create'])->name('admin.blognewcategory');
Route::post('/admin/blog/savecategory', [BlogCategoryController::class, 'save'])->name('admin.blogsavecategory');
Route::get('/admin/blog/editcategory/{id}', [BlogCategoryController::class, 'edit'])->name('admin.blogeditcategory');
Route::post('/admin/blog/updatecategory/{id}', [BlogCategoryController::class, 'update'])->name('admin.blogupdatecategory');
Route::post('/admin/blog/categorystatus', [BlogCategoryController::class, 'updatestatus'])->name('admin.blogcategorystatus');
Route::post('/admin/blog/categorydelete', [BlogCategoryController::class, 'delete'])->name('admin.blogcategorydelete');

Route::get('/admin/blog/tags', [BlogTagController::class, 'index'])->name('admin.blogtags');
Route::get('/admin/blog/newtag', [BlogTagController::class, 'create'])->name('admin.blognewtag');
Route::post('/admin/blog/savetag', [BlogTagController::class, 'save'])->name('admin.blogsavetag');
Route::get('/admin/blog/edittag/{id}', [BlogTagController::class, 'edit'])->name('admin.blogedittag');
Route::post('/admin/blog/updatetag/{id}', [BlogTagController::class, 'update'])->name('admin.blogupdatetag');
Route::post('/admin/blog/tagstatus', [BlogTagController::class, 'updatestatus'])->name('admin.blogtagstatus');
Route::post('/admin/blog/tagdelete', [BlogTagController::class, 'delete'])->name('admin.blogtagdelete');

Route::get('/admin/blog/posts', [BlogPostController::class, 'index'])->name('admin.blogposts');
Route::get('/admin/blog/newpost', [BlogPostController::class, 'create'])->name('admin.blognewpost');
Route::post('/admin/blog/savepost', [BlogPostController::class, 'save'])->name('admin.blogsavepost');
Route::get('/admin/blog/editpost/{id}', [BlogPostController::class, 'edit'])->name('admin.blogeditpost');
Route::post('/admin/blog/updatepost/{id}', [BlogPostController::class, 'update'])->name('admin.blogupdatepost');
Route::post('/admin/blog/poststatus', [BlogPostController::class, 'updatestatus'])->name('admin.blogpoststatus');
Route::post('/admin/blog/postdelete', [BlogPostController::class, 'delete'])->name('admin.blogpostdelete');

