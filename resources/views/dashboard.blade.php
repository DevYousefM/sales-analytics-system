@extends('layout.master')

@section('content')
    <div
        class="text-[13px] leading-[20px] flex-1 p-4 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg ">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Total Revenue</h3>
                <p class="text-2xl font-bold text-[#2e2d2b] dark:text-white" id="total-revenue">
                    ${{ number_format($total_revenue, 2) }}</p>
            </div>

            <div class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Orders in Last Minute</h3>
                <p class="text-2xl font-bold text-[#2e2d2b] dark:text-white" id="orders-count">
                    {{ $orders_count_in_last_minute }}</p>
            </div>

            @php
                $isIncrease = $revenue_change_in_last_minute > 0;
            @endphp
            <div class="md:col-span-2">
                <div
                    class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        Revenue Change in Last Minute
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-2xl font-bold
                            {{ $isIncrease ? 'text-green-600' : 'text-red-500' }}"
                            id="revenue-change">
                            ${{ number_format(abs($revenue_change_in_last_minute), 2) }}
                        </span>
                        <span
                            class="text-xl
                            {{ $isIncrease ? 'text-green-600' : 'text-red-500' }}"
                            id="revenue-change-indicator">
                            {!! $isIncrease ? '▲' : '▼' !!}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400" id="revenue-change-label">
                            {{ $isIncrease ? 'Increased' : 'Decreased' }} from the previous minute
                        </span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Top Products</h3>
                <div class="space-y-2" id="top-products">
                    @foreach ($top_products as $product)
                        <div id="top-product-{{ $product->product_id }}">
                            <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                                <span>{{ $product->product_name }}</span>
                                <span
                                    id="top-product-quantity-{{ $product->product_id }}">{{ $product->total_quantity }}</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded">
                                <div class="h-2 bg-blue-500 rounded"
                                    style="width: {{ min(100, ($product->total_quantity / $top_products[0]->total_quantity) * 100) }}%"
                                    id="top-product-bar-{{ $product->product_id }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush
