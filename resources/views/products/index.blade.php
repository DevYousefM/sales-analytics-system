@extends('layout.master')

@section('content')
    <div class="flex flex-col gap-4 w-full">
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 pb-0 px-8 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <h1 class="mb-1 font-medium text-lg text-center ">Add Product</h1>
            <form id="product-form" class="mb-6 lg:mb-6">
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
                            <label for="name" class="text-sm text-white">Name</label>
                            <input type="text" id="name" name="name"
                                class=" dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] text-sm outline-none text-white py-1 px-2 transition-colors duration-200 ease-in-out rounded-lg w-full bg-[#3e3e3a] border border-[#3E3E3A]">
                        </div>
                    </li>
                    <li
                        class="flex items-start gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1 before:top-0 before:left-[0.4rem] before:absolute before:h-full">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <label class="text-sm text-white">Description</label>
                            <textarea name="description" cols="20" rows="10"
                                class="pt-3 dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] text-sm outline-none text-white py-1 px-2 leading-1 transition-colors duration-200 ease-in-out rounded-lg w-full resize-none bg-[#3e3e3a] border border-[#3E3E3A]"></textarea>
                        </div>
                    </li>
                    <li
                        class="flex items-start gap-4 py-2  relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1 before:top-0 before:left-[0.4rem] before:absolute before:h-full">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <label class="text-sm text-white">Price</label>
                            <input type="number" name="price" id="price"
                                class=" dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] text-sm outline-none text-white py-1 px-2 transition-colors duration-200 ease-in-out rounded-lg w-full bg-[#3e3e3a] border border-[#3E3E3A]">
                        </div>
                    </li>
                    <li
                        class="flex items-start gap-4 py-1 mb-5 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1 before:top-0 before:left-[0.4rem] before:absolute before:h-full">
                        <span class="relative py-1 bg-white dark:bg-[#161615]">
                            <span
                                class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                            </span>
                        </span>
                        <div class="flex flex-col gap-1 w-full">
                            <label class="text-sm text-white">Temp Category</label>
                            <select name="temp_category" id="temp_category"
                                class="dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] text-sm outline-none text-white py-1 px-2 transition-colors duration-200 ease-in-out rounded-lg w-full bg-[#3e3e3a] border border-[#3E3E3A]">
                                <option value="">Select Category</option>
                                @foreach (\App\Enum\TempCategoryEnum::cases() as $category)
                                    <option value="{{ $category->value }}">{{ ucfirst(strtolower($category->value)) }}
                                    </option>
                                @endforeach
                            </select>
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
                                Add Product
                            </button>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
        <div
            class="text-[13px] leading-[20px] flex-1 p-6 lg:p-12 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-lg lg:rounded-lg ">
            <h1 class="mb-5 font-medium text-lg text-center">Products</h1>
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
@push('scripts')
    @vite('resources/js/products.js')
@endpush
