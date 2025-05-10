@extends('layout.master')

@section('content')
    <div class="flex flex-col gap-4 w-full">
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <h1 class="mb-3 font-medium text-lg text-center">Orders</h1>
            <nav class="flex items-center justify-end gap-4 mb-3 w-full">
                <a href="{{ route('orders.create') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Add Order
                </a>
            </nav>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="order-list">
                @forelse ($orders as $order)
                    <div
                        class="flex items-start gap-4 p-6 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md bg-white dark:bg-[#1e1e1e] transition hover:shadow-lg">
                        <div class="flex flex-col gap-3 w-full">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Product: {{ $order->product->name }}
                            </h3>
                            <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <div class="font-medium">Product ID:</div>
                                <div>{{ $order->product_id }}</div>

                                <div class="font-medium">Price:</div>
                                <div>${{ number_format($order->price, 2) }}</div>

                                <div class="font-medium">Quantity:</div>
                                <div>{{ $order->quantity }}</div>

                                <div class="font-medium">Order Date:</div>
                                <div>{{ $order->date }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center" id="no-orders">No orders found</div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/orders/orders.js')
@endpush
