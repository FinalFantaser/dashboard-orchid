<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'name', 'phone', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime:H:i:s d.m.Y',
    ];

    protected $allowedSorts = [
        'name',
        'phone',
        'status',
        'created_at',
    ];

    protected $allowedFilters = [
        'status',
        'created_at'
    ];

    public const STATUS_NEW = 'new'; //Такой лид уже есть в БД, и это последнее его вхождение
    public const STATUS_REPEAT = 'repeat'; //Повтор данный лид уже присутствует в БД, но есть более новый
    public const STATUS_FIRST = 'first'; //Первое вхождение лида (без ранних повторов)
    
    //TODO Поиск с фильтром
    public function scopeNew($query) //Поиск лидов без повторов
    {
        $query->whereIn('status', [Order::STATUS_FIRST, Order::STATUS_NEW]);
    } //scopeNew
}
