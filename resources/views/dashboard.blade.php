@extends('layout.master')

@section('content')
    <div
        class="text-[13px] leading-[20px] flex-1 p-4 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg ">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Total Revenue</h3>
                <p class="text-2xl font-bold text-[#2e2d2b] dark:text-white" id="total-revenue">
                </p>
            </div>

            <div class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">Orders in Last Minute</h3>
                <p class="text-2xl font-bold text-[#2e2d2b] dark:text-white" id="orders-count">
            </div>

            <div class="md:col-span-2">
                <div
                    class="p-4 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        Revenue Change in Last Minute
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold" id="revenue-change">

                        </span>
                        <span class="text-xl
                          " id="revenue-change-indicator">
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400" id="revenue-change-label">
                        </span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Top Products</h3>
                <div class="space-y-2" id="top-products">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush
