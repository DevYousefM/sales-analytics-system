@extends('layout.master')

@section('content')
    <div class="flex flex-col gap-4 w-full">
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <p class="font-medium text-lg text-center">
                With the current temperature at
            </p>
            <div class="flex items-center justify-center my-5 ">
                <div
                    class="w-fit font-bold text-2xl {{ $temp_category === \App\Enum\TempCategoryEnum::HOT->value ? 'text-red-600' : 'text-blue-600' }}
                     pl-3 pr-4 pt-4 pb-4 border border-gray-200 dark:border-gray-700 rounded-4xl">
                    <span>
                        °{{ $temp }}
                    </span>
                </div>
            </div>

            <p class="mb-5 font-medium text-lg text-center">
                here are some personalized suggestions to suit the weather
            </p>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="product-list">
                @foreach ($products as $product)
                    <div
                        class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm bg-white dark:bg-[#1e1e1e]">
                        <div class="flex flex-col gap-2 w-full">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $product->description }}
                            </p>
                            <p class="text-base font-bold text-green-600 dark:text-green-400">
                                ${{ $product->price }}
                            </p>
                            <p
                                class="text-base font-bold {{ $product->temp_category === \App\Enum\TempCategoryEnum::HOT->value ? 'text-red-600' : 'text-blue-600' }}">
                                {{ $product->temp_category }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
