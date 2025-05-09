@extends('layout.master')

@section('content')
    <div
        class="text-[13px] leading-[20px] flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
        <h1 class="mb-5 font-medium text-lg text-center">Products</h1>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="product-list">
            <div
                class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] animate-pulse">
                <div class="flex flex-col gap-2 w-full">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] animate-pulse">
                <div class="flex flex-col gap-2 w-full">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>

                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>

                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] animate-pulse">
                <div class="flex flex-col gap-2 w-full">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>

                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>

                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                </div>
            </div>
            <div
                class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e] animate-pulse">
                <div class="flex flex-col gap-2 w-full">
                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>

                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>

                    <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/recommendations.js')
@endpush
