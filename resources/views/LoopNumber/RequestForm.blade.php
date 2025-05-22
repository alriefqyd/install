@extends('main')
@section('main')
    <!-- Section with Wavy Background -->
    <div class="relative w-full min-h-screen flex flex-col items-center justify-start pt-20 bg-white">
    <!-- Top Wave -->
    <svg class="absolute top-0 right-0 w-full h-74 z-0" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#0f766e" fill-opacity="1"
              d="M0,150 C360,280 1080,40 1440, 210 L1440,0 L0,0 Z"></path>
    </svg>
    <!-- Header Content -->
    <div class="relative z-10 text-center text-white px-6 max-w-3xl">
        <div class="flex items-center justify-center space-x-4 mb-1">
            <h1 class="text-4xl text-white font-bold">Request Loop Number</h1>
        </div>
        <p class="text-lg text-teal-100">Submit new loop numbers, manage requests, and streamline operations from one place.</p>
    </div>

    <!-- Category Selection -->
    <div class="z-10 mt-10 w-full max-w-7xl px-6 js-area-select">
        @if(session('success'))
            <div id="notif-card" class="fixed top-5 right-5 z-50 bg-white border border-green-300 text-green-800 p-4 rounded-xl shadow-lg w-80 flex items-start space-x-3">
                <svg class="w-6 h-6 text-green-600 mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">Success</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('notif-card').remove()" class="ml-auto text-green-600 hover:text-green-800">
                    &times;
                </button>
            </div>

            <script>
                setTimeout(() => {
                    const card = document.getElementById('notif-card');
                    if(card) card.remove();
                }, 5000);
            </script>
        @endif
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-10">
            <h2 class="text-3xl font-bold text-teal-800 mb-10 text-center">Select Area</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Card Start -->
                <button data-value="process_plant" class="js-area-button relative group rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all">
                    <img src="/images/factory.jpg" alt="Process Plant" class="w-full h-60 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-60 transition duration-300">
                        <p class="text-white text-xl font-semibold tracking-wide">PROCESS PLANT</p>
                    </div>
                </button>

                <button data-value="hydro" class="js-area-button relative group rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all">
                    <img src="/images/hydro.jpg" alt="Hydro" class="w-full h-60 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-60 transition duration-300">
                        <p class="text-white text-xl font-semibold tracking-wide">HYDRO</p>
                    </div>
                </button>

                <button data-value="mining" class="js-area-button relative group rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all">
                    <img src="/images/mining.jpg" alt="Mechanical" class="w-full h-60 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-60 transition duration-300">
                        <p class="text-white text-xl font-semibold tracking-wide">MINING</p>
                    </div>
                </button>

                <button data-value="others" class="js-area-button relative group rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all">
                    <img src="/images/others.jpg" alt="Electrical" class="w-full h-60 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-60 transition duration-300">
                        <p class="text-white text-xl font-semibold tracking-wide">OTHERS</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <!-- Form -->
    <div class="relative js-form-request-container z-10 flex flex-col lg:flex-row w-full max-w-7xl py-5 gap-10 hidden">

        <!-- Right: SVG Illustration -->
        <div class="w-full lg:w-1/2 flex items-center justify-center">
            <img src="images/engineer2.png" alt="Engineer working on PC"
                 class="max-h-[400px] w-auto object-contain drop-shadow-2xl" />
        </div>

        <!-- Left: Form Panel -->
        <div class="w-full lg:w-1/2 bg-gray-100 rounded-2xl shadow-2xl p-10 flex flex-col justify-between" style="min-height: 500px;">
            <form class="js-form-request space-y-5" action="/request" method="POST" enctype="multipart/form-data">
                <a href=""
                   class="js-form-back inline-flex items-center text-sm font-medium text-red-500 hover:text-teal-900 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
                @csrf
                <input type="hidden" name="area" class="js-area-form">
                <div>
                    <label class="block text-sm font-semibold text-teal-900 mb-1">Request By</label>
                    <select name="engineer" class="w-full px-4 py-2 border border-teal-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                        @foreach($engineers as $en)
                            <option value="{{$en->id}}">{{$en->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-teal-900 mb-1">Upload P&ID Drawing <span class="text-red-500">*</span></label>
                    <input type="file" name="p_and_id"
                           class="w-full px-4 py-2 border border-teal-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-teal-900 mb-1">Upload HMI Screenshot</label>
                    <input type="file" name="hmi"
                           class="w-full px-4 py-2 border border-teal-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" >
                </div>

                <div class="flex justify-end pt-4">
                    <button
                        type="submit"
                        id="submitBtnRequest"
                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-teal-700 text-white font-semibold hover:bg-teal-800 disabled:opacity-50"
                    >
                        <svg
                            id="spinner"
                            class="w-4 h-4 animate-spin text-white hidden"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v8H4z"
                            ></path>
                        </svg>
                        <span class="btn-title"> Submit Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


