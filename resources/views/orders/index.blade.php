@extends('layout.master')

@section('content')
    <div class="flex flex-col gap-4 w-full">
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 pb-0 px-8 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <h1 class="mb-1 font-medium text-lg text-center ">Make Order</h1>
            <form id="order-form" class="mb-6 lg:mb-6">
                <ul class="flex flex-col">
                    <li
                        class="flex items-start gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/4 before:bottom-0 before:left-[0.4rem] before:absolute">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <label for="name" class="text-sm text-white">Product</label>
                            <div class="relative w-full">
                                <button id="dropdownToggle" type="button"
                                    class="w-full bg-[#3e3e3a] text-white text-left px-4 py-2 rounded-lg">
                                    Select a Product
                                </button>

                                <ul id="products_list"
                                    class="hidden absolute left-0 bg-[#3e3e3a] text-white p-2 rounded-lg mt-2 w-full z-10 h-[150px] overflow-y-scroll">
                                </ul>
                            </div>

                            <input type="hidden" id="product_id" name="product">
                        </div>
                    </li>
                    <li
                        class="flex items-start gap-4 py-2 mb-5 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1 before:top-0 before:left-[0.4rem] before:absolute before:h-full">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <label class="text-sm text-white">Quantity</label>
                            <input type="number" name="quantity" id="Quantity"
                                class=" dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] text-sm outline-none text-white py-1 px-2 transition-colors duration-200 ease-in-out rounded-lg w-full bg-[#3e3e3a] border border-[#3E3E3A]">
                        </div>
                    </li>
                    <li
                        class="flex items-center gap-4 py-2 mb-3 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-8 before:left-[0.4rem] before:absolute before:h-full">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <button type="button" id="submit-button"
                                class="hover:cursor-pointer inline-block px-5 py-1.5 dark:text-[#EDEDEC] hover:border-[#1915014a] border text-[#1b1b18] dark:hover:border-[#62605b] hover:bg-[#eeeeec] hover:text-black rounded-sm text-sm leading-normal">
                                Make Order
                            </button>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <h1 class="mb-5 font-medium text-lg text-center">Orders</h1>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="product-list">
                @foreach ($orders as $order)
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
                @endforeach
            </div>
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endsection
    @push('scripts')
        @vite('resources/js/orders.js')
    @endpush
