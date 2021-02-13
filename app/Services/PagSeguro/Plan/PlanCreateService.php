<?php


namespace App\Services\PagSeguro\Plan;

use App\Services\PagSeguro\Credentials;
use Illuminate\Support\Facades\Http;

class PlanCreateService
{

    public function makeRequest(array $data)
    {

        $url = Credentials::getCrentials('/pre-approvals/request/');

        $response = Http::withHeaders([
            'Accept'        => 'application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1',
            'Content-Type'  => 'application/json'
        ])->post(
            $url,
            [
                'reference'     => $data['slug'],
                'preApproval'   => [
                    'name'              => $data['name'],
                    'charge'            => 'AUTO',
                    'period'            => 'MONTHLY',
                    'amountPerPayment'  => number_format(($data['price'] / 100), 2)
                ]
            ]
        );

        return $response->json()['code'];

    }

}
