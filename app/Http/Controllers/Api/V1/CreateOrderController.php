<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\CreateRequest;
use App\Services\IntegrationService;
use App\Services\OrderService;
use App\Services\SettingsService;
use Illuminate\Http\Response;

class CreateOrderController extends Controller
{
    private SettingsService $settings;

    public function __construct(
        private OrderService $service,
        private IntegrationService $integration,
    )
    {
        $this->settings = SettingsService::getInstance();
    } //Конструктор

    public function __invoke(CreateRequest $request)
    {
        if($this->settings->crm_enabled)
            $this->integration->sendToCRM($request);
        

        $this->service->leadAdd($request);
        
        return response(content: 'Лид создан', status: Response::HTTP_CREATED);
    } //__invoke
}
