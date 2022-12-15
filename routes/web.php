<?php
use Illuminate\Support\Facades\Route;
use App\Model\ProvinceCity;
use Intervention\Image\ImageManagerStatic as Image;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect()->route('user.index');
});

// Route::get('/fakeLog/{id?}', function ($id=2) {
//     auth()->loginUsingId( $id , true);
//     return redirect()->route('user.index');
// });

Route::get('city-ajax/{id}', function ($id) {
    $city = ProvinceCity::where('parent_id', $id)->get();
    return $city;
});
Route::get('tests', function () {
    img_resize(
        'source/asset/uploads/service_package/1399-09-01/photos/pic_card-4f74826cadb2eb004022c8d2d22040a2.png', //address img
        'source/asset/resize-4f74826cadb2eb004022c8d2d22040a2.png', //address save
        50,// width: if width==0 -> width=auto
        0 // height: if height==0 -> height=auto
    // end optimaiz
    );
    dd('ok');
});




