<?php

namespace App\Services;

use App\Integrations\OpenAI;
use App\Repositories\OrderRepository;

class IntegrationWithAIService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getRecommendationsWithAI()
    {
        $response = $this->sendSalesDataToAI();
        return $response;
    }
    public function sendSalesDataToAI()
    {
        $prompt = $this->buildPrompt();
        return OpenAI::GetRecommendationsWithAI($prompt);
    }
    private function buildPrompt()
    {
        $orders = $this->orderRepository->getOrderSentToAI();

        $prompt  = "Based on the following sales data, identify the products (by product_id) with the lowest total sales revenue (price × quantity). ";
        $prompt .= "These are the underperforming products we should consider promoting to improve their sales. ";
        $prompt .= "Return ONLY a ranked array from lowest to highest revenue using this format:\n";
        $prompt .= '[{"product_id": 2, "total_revenue": 1174.04}, ...]';
        $prompt .= "\nDo not include any explanation or extra text — only return the array.\n";
        $prompt .= "\nDo not return empty array.\n";
        $prompt .= "Sales data: ";
        $prompt .= json_encode($orders);

        return $prompt;
    }
}
