<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Orchid\Layouts\Order\OrderListLayout;
use App\Services\OrderService;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;


class PlatformScreen extends Screen
{
    public function __construct(
        private OrderService $service
    )
    {}

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'orders' => $this->service->orderList(),
        ];
    }

    public function asyncGetOrder(int $id)
    {
        return [
            'order' => $this->service->findById(id: $id, fail: true),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Журнал лидов';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Список лидов, полученных с сайта';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            OrderListLayout::class,
        ];
    }
}
