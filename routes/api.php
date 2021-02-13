<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/notifications', function (Request $request){

    $data = $request->all('notificationType', 'notificationCode');

    // notificationType: preApproval
    // notificationCode: O código da notificação

    $subscription = (new App\Services\PagSeguro\Subscription\SubscriptionReaderService())->getSubscriptionNotificationCode($data['notificationCode']);

    if(isset($subscription['error']) && $subscription['error'])
        return response()->json(['data'  => ['msg'   => 'Nada encontrado!']], 404);

    // Pegar o plano do usuário, o qual ele esta participando
    $userPlan = \App\Models\UserPlan::whereReferenceTransaction($subscription['code'])->first();

    if(!$userPlan)
        return response()->json(['data'  => ['msg'   => 'Nada encontrado!']], 404);

    $userPlan->update(['status' => $subscription['status']]);

    if($subscription['status'] === 'ACTIVE'){
        // Enviar um e-mail para o usuário agradecendo a adesão...
    }

    if($subscription['status'] === 'ACTIVE'){
        // Enviar um e-mail para o usuário pedindo desculpas pois não foi possivel renovar o plano...
    }

    return response()->json([], 204);

});
