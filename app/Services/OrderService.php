<?php

namespace App\Services;

use App\Http\Requests\Api\V1\Order\CreateRequest;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService{
    public const PER_PAGE = 10;


    /**
     *      CUD-методы
     */
    
    public function create(string $name, string $phone): Order
    {
        $status = Order::STATUS_FIRST;
        $previousOrders = $this->findByPhone($phone);

        if($previousOrders->isNotEmpty())
        {
            $status = Order::STATUS_NEW;
            $this->query()->where(['phone' => $phone])
                ->where('status', '!=', Order::STATUS_FIRST)
                ->lazyById(chunkSize: 500, column: 'id')
                ->each->update(['status' => Order::STATUS_REPEAT]);
        }

        return Order::create(['name' => $name, 'phone' => $phone, 'status' => $status]);
    } //create

    public function update(Order|int $order, string $status): Order
    {
        if(is_int($order))
            $order = $this->findById(id: $order, fail: true);

        $order->update(['status' => $status]);

        return $order;
    } //update

    public function remove(Order|int $order): void
    {
        if(is_numeric($order))
            $this->query()->where('id', $order)->delete();
        elseif($order instanceof Order)
            $order->delete();
    } //remove

    /**
     *      R-Методы
     */
    public function query(): Builder
    {
        return Order::query()->filters();
    } //query

    public function findById(int $id, bool $fail = false): ?Order
    {
        $query = $this->query()->where('id', $id);
        return $fail ? $query->firstOrFail() : $query->first();
    } //findById

    public function findByPhone(string $phone): Collection
    {
        return $this->query()->where('phone', $phone)->get();
    } //findByName

    /**
     *      Методы для экранов Orchid и контроллеров
     */
    public function orderList(bool $unique = false): LengthAwarePaginator //
    {
        return $this->query()->defaultSort('created_at', 'desc')
        ->when(value: $unique, callback: function($query){
            return $query->new();
        })
        ->latest()
        ->paginate(self::PER_PAGE);
    } //orderList

    public function leadAdd(CreateRequest $request): Order
    {
        return $this->create(name: $request->name, phone: $request->phone);
    } //leadAdd

    /**
     *      Другие методы
     */
    public function resetStatuses(): void //Вернуть всем лидам статус на new
    {
        $this->query()->where(column: 'status', operator: '!=', value: Order::STATUS_NEW)
            ->lazyById()
            ->each->update(['status' => Order::STATUS_NEW]);
    } //makeAllNew
};

?>