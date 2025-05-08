@extends('layout.master')

@section('content')
    <div
        class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
        <h1 class="mb-1 font-medium">Let's get started</h1>
        <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Laravel has an incredibly rich ecosystem. <br>We suggest starting
            with the following.</p>
        <ul class="flex flex-col mb-4 lg:mb-6">
            <li
                class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                <span class="relative py-1 bg-white dark:bg-[#161615]">
                    <span
                        class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                        <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                    </span>
                </span>
                <span>
                    Read the
                    <a href="https://laravel.com/docs" target="_blank"
                        class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                        <span>Documentation</span>
                        <svg width="10" height="11" viewBox="0 0 10 11" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5">
                            <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor"
                                stroke-linecap="square" />
                        </svg>
                    </a>
                </span>
            </li>
            <li
                class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1/2 before:top-0 before:left-[0.4rem] before:absolute">
                <span class="relative py-1 bg-white dark:bg-[#161615]">
                    <span
                        class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                        <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                    </span>
                </span>
                <span>
                    Watch video tutorials at
                    <a href="https://laracasts.com" target="_blank"
                        class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                        <span>Laracasts</span>
                        <svg width="10" height="11" viewBox="0 0 10 11" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5">
                            <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor"
                                stroke-linecap="square" />
                        </svg>
                    </a>
                </span>
            </li>
        </ul>
        <ul class="flex gap-3 text-sm leading-normal">
            <li>
                <a href="https://cloud.laravel.com" target="_blank"
                    class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                    Deploy now
                </a>
            </li>
        </ul>
    </div>
@endsection
