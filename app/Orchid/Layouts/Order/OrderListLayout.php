<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Order;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class OrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make(name: 'name', title: 'Имя')->sort(),
            TD::make(name: 'phone', title: 'Телефон')->sort(),

            TD::make(name: 'status', title: 'Статус')
                ->render(function($order){ return __($order->status);})
                ->filter(
                    TD::FILTER_SELECT,
                    [
                        Order::STATUS_NEW => 'Новый',
                        Order::STATUS_REPEAT => 'Повтор',                        
                        Order::STATUS_FIRST => 'Первый',
                    ]
                )
                ->sort(),

            TD::make(name: 'created_at', title: 'Дата')->filter(DateRange::make('created_at')->title('Диапазон дат'))->sort(),
            TD::make()->render(function($order){
                return Link::make()->icon('trash')->route(name: 'order.destroy', parameters: ['order' => $order->id]);
            }),
        ];
    }

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return 'Лиды отсутствуют';
    }

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return true;
    }
}
