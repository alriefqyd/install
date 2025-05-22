@extends('main')
@section('main')
<!-- Section with Wavy Background -->
<div class="relative w-full h-screen bg-teal-700 overflow-hidden">

    <!-- SVG Wavy Shape -->
    <svg class="absolute top-0 right-0 w-full h-full" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#fefaf3" fill-opacity="1" d="M0,160 C360,280 1080,40 1440,160 L1440,320 L0,320 Z"></path>
    </svg>

    <!-- Content Wrapper -->
    <div class="relative z-[10] flex flex-col lg:flex-row h-full items-center justify-between px-10">

        <!-- Left Image -->
        <div class="w-full lg:w-1/2 flex justify-center items-center h-full">
            <img src="images/engineer.png" alt="Instrument Engineer" class="max-h-[80%] drop-shadow-2xl" />
        </div>

        <!-- Right Text Panel -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center h-full px-7 lg:px-16 text-[#f9f1df]">
            <h1 class="text-3xl mb-12 font-extrabold drop-shadow-sm">INSTALL (Instrument Index For All)</h1>
            <p class="text-lg mb-6 mt-6 text-[#0f4f49] max-w-md leading-relaxed">
                Choose your action below to manage loop numbers and instrument indexes efficiently.
            </p>

            <div class="flex gap-4 flex-wrap">
                <!-- Request Loop No Button -->
                <a href="/request-loop-no">
                    <button class="bg-yellow-400 hover:bg-yellow-300 text-teal-900 px-6 py-3 rounded-lg font-bold shadow-md transition-all duration-200">
                        Request Loop No
                    </button>
                </a>

                <!-- Instrument Index Dropdown -->
                <div class="relative">
                    <!-- Button -->
                    <button id="dropdownToggle" class="bg-white hover:bg-gray-100 text-teal-700 px-6 py-3 rounded-lg font-bold shadow-md transition-all duration-200 flex items-center gap-2">
                        Instrument Index
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="absolute hidden bg-white shadow-lg rounded-lg mt-2 w-56 z-[10]">
                        <a href="/update-instrument-index"
                           class="block px-4 py-2 text-teal-700 hover:bg-gray-100 rounded-t-lg">
                            Update Instrument Index
                        </a>
                        <a href="/view-instrument-index"
                           class="block px-4 py-2 text-teal-700 hover:bg-gray-100 rounded-b-lg">
                            View Instrument Index
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

