<?php

namespace App\Services;

use App\Mail\NewOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailService{
    /**
     *      Свойства
     */
    private SettingsService $_settings;

    /**
     *      Открытые методы
     */
    public function __construct()
    {
        $this->_settings = SettingsService::getInstance();
        $this->_loadConfig();
    } //Конструктор

    public function send(Order $order): void //Отправить уведомление на почту
    {
        Mail::to($this->_settings->email_to_address)->send(new NewOrder($order));
    } //send

    /**
     *      Скрытые рабочие методы
     */
    private function _loadConfig(): void //Загрузить настройки email в мейлер dynamic_smtp
    {
        //Загрузка настроек отправителя из config/mail.php
        $path = 'mail.from.';
        Config::set($path.'address', $this->_settings->email_from_address);
        Config::set($path.'name', $this->_settings->email_from_name);

        //Загрузка настроек мейлера dynamic_smtp
        $path = 'mail.mailers.dynamic_smtp.';
        Config::set($path.'host', $this->_settings->email_host);
        Config::set($path.'port', $this->_settings->email_port);
        Config::set($path.'username', $this->_settings->email_username);
        Config::set($path.'password', $this->_settings->email_password);
        Config::set($path.'from_name', $this->_settings->email_from_name);
        Config::set($path.'from_address', $this->_settings->email_from_address);
    } //_loadConfig
};

?>