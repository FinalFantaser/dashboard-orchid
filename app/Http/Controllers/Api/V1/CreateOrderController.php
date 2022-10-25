<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\CreateRequest;
use App\Services\OrderService;
use Illuminate\Http\Response;

class CreateOrderController extends Controller
{
    public function __construct(
        private OrderService $service
    )
    {} //Конструктор

    public function __invoke(CreateRequest $request)
    {
        $this->service->leadAdd($request);
        return response(content: 'Лид создан', status: Response::HTTP_CREATED);
    } //__invoke
}
