<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Spatie\Valuestore\Valuestore;

class SettingsService{ //Класс является синглтоном
    /**
     *      Свойства
     */
    private static $_instance; // Экземпляр синглтона
    
    private const _FILEPATH = 'settings.json';
    private const _DEFAULTS = [
        'email_enabled' => false,
        'email_host' => '<email_host>',
        'email_port' => '<email_port>',
        'email_username' => '<email_username>',
        'email_password' => '<email_password>',
        'email_from_address' => 'mafia@pizza.ru',
        'email_from_name' => 'Турция квартиры',
        'email_to_address' => '<email_receiver>', //Адрес, на который приходит уведомление
        'email_subject' => 'Новая заявка!',
    ];

    private $_file;

    /**
     *      Открытые методы
     */
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new self;
            
            if(!Storage::disk('local')->exists(self::_FILEPATH))
            self::generateFile();
            
            self::$_instance->_file = Valuestore::make(storage_path('app/' . self::_FILEPATH));
        }
        
        return self::$_instance;
    } //getInstance
    
    public static function generateFile(): void //Создать файл со значениями по умолчанию
    {
        $file = Valuestore::make(storage_path('app/' . self::_FILEPATH));
        $file->put(self::_DEFAULTS);
    } //generateFile
    
    public function getAll(): array //Загрузить все настройки из файла
    {
        return $this->_file->all();
    } //getAll

    public function setMassive(array $values): void //Записать в файл сразу несколько значений
    {
        $this->_file->put($values);
    } //setMassive

    public function reset(): void //Сброс настроек
    {
        Storage::disk('local')->delete(self::_FILEPATH);
        self::generateFile();
    } //reset

    public function __get(string $name): mixed
    {
        if(!$this->_file->has($name) && !Arr::has(self::_DEFAULTS, $name))
            throw new \Exception(message: 'В настройках отсутствует ключ ' . $name);

        return $this->_file->get($name, self::_DEFAULTS[$name]);
    } //__get

    public function __set(string $name, mixed $value): void
    {
        $this->_file->set([$name => $value]);
    } //__set

    public function __isset(string $name): bool
    {
        return $this->_file->has($name);
    } //__isset

    public function __unset(string $name): void
    {
        if($this->_file->has($name))
            $this->_file->forget($name);
    } //__unset
    /**
     *      Скрытые методы
     */
    private function __construct(){}
    private function __clone(){}
    public function __wakeup(){}

};

?>