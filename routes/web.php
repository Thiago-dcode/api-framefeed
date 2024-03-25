<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Goto_;

use function PHPUnit\Framework\fileExists;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

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



Route::get('/', function () {



    $image =   env('PUBLIC_STORAGE') . "/userAvatar/default.png";
    fileExists($image);
   
});
