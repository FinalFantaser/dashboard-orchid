<?php

namespace App\Services;

use App\Http\Requests\Api\V1\Order\CreateRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IntegrationService{
    private SettingsService $settings;

    public function __construct()
    {
        $this->settings = SettingsService::getInstance();
    } //Конструктор

    public function sendToCRM(CreateRequest $request): void
    {
        if(!$this->settings->crm_enabled) //Если интеграция отключена, ничего не делать
            return;

        //Формирование данных
        $data = [
            'api_token' => $this->settings->project_token,
            'name' =>$request->has('name') ? $request->input('name') : 'Аноним',
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'cost' => $request->input('summa'),
            'comment' => $request->has('data') ? $request->input('data') : '',
            'host' => $request->input('host'),
            'ip' => $this->getIp(),
            'referrer' => $request->input('referrer'),
            'url_query_string' => $request->input('url_query_string'),
        ];

        //Отправка запроса
        $response = Http::withOptions(['verify' => false])->post($this->settings->crm_url, $data);
        Log::info($response);
    } //sendToCRM

    private function getIp(): string
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        elseif(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        return $ipaddress;
    } //getIp
};

?>