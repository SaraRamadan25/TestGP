<?php

use App\Http\Controllers\FaceBookController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HeartRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\SensorController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getData', [SensorController::class, 'getData']);

Route::post('/sensor-data', [SensorController::class, 'sensorData']);


Route::get('/heart-rate', [HeartRateController::class, 'getHeartRate']);
Route::post('/positionstack-api',[LocationController::class, 'positionStack']);
Route::get('/positionstack', [LocationController::class, 'positionStack']);
Route::post('/arcgis-api',[LocationController::class, 'arcgis']);



Route::get('/',[HomeController::class,'index'])->name('home');
// Other routes
Route::get('/generate-qrcode/{id}', [QrcodeController::class, 'generateQrCode'])->name('generate.qrcode');
Route::get('/share-qrcode/{receiver_user_id}', [QrcodeController::class, 'shareQRCode'])->name('share.qrcode');




Route::post('/paypal/create-payment', [PayPalController::class, 'createPayment']);
Route::post('/paypal/execute-payment', [PayPalController::class, 'executePayment']);
Route::get('login/facebook', [FaceBookController::class, 'redirectToFacebook'])->name('login.facebook')->middleware('web');
Route::get('login/facebook/callback', [FaceBookController::class, 'handleFacebookCallback'])->middleware('web');


/*
Route::get('/auth/redirect', function () {
    return Socialite::driver('facebook')->stateless()->redirect();
})->middleware('web');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('facebook')->stateless()->user();
    dd($user);
})->middleware('web');*/

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->stateless()->redirect();
})->middleware('web');

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->stateless()->user();

    $user = User::where('email', $githubUser->email)->first();

    if (!$user) {
        $user = new User();
        $user->name = $githubUser->name;
        $user->email = $githubUser->email;
        // Add any other fields you want to save
        $user->save();
    }

    Auth::login($user);
    return redirect()->route('home');

})->middleware('web');


Route::post('health',[HealthController::class,'saveHealthData']);
