<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function createNewOrder(Request $request)
    {
        $this->orderRepository->insertOrderToDB($request->validated());

        $id = $this->orderRepository->getLastOrderID();

        $order = $this->orderRepository->getOrderByID($id);

        return $order[0];
    }
}
