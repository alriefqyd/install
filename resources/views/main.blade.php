<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Instrument Engineer Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        fieldset {
            border: none;
            margin-bottom: 2.5rem !important;
        }
        legend {
            font-weight: bold;
            margin-bottom: 1rem !important;
            font-size: 1.1rem;
            color: #444;
            border-left: 4px solid #009f91;
            padding-left: 0.6rem !important;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .form-field {
            flex: 1 1 48%;
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            color: #333;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f0ea;
        }
        input[type="text"], textarea {
            padding: 0.6rem;
            border: 1px solid #a3d6ce;
            border-radius: 6px;
            font-size: 0.95rem;
            background-color: #f8fefc;
        }
        textarea {
            resize: vertical;
        }
        .submit-btn {
            margin-top: 2rem;
            text-align: right;
        }
        button[type="submit"] {
            background-color: #009f91;
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }
        .tab-btn.active-tab {
            border-color: #009f91;
            font-weight: bold;
            color: #009f91;
        }
        .tab-btn.active-tab {
            background-color: #009199; /* light teal background */
            border-color: rgba(137, 214, 197, 0.5);
            font-weight: bold;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
        /* Sidebar slide + scale + fade animation */
        @keyframes slideInScale {
            0% {
                transform: translateX(-100%) scale(0.95);
                opacity: 0;
            }
            100% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        #sidebar.open {
            animation: slideInScale 0.4s ease forwards;
        }

        /* Custom scrollbar */
        #sidebar::-webkit-scrollbar {
            width: 8px;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        #sidebar::-webkit-scrollbar-track {
            background-color: transparent;
        }

        /* Sidebar left edge color bar */
        #sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 5px;
            background: linear-gradient(180deg, #f1bf08, #f59e0b);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            pointer-events: none;
        }
    </style>
</head>
<body>

<!-- Sidebar Toggle & Sidebar -->
<div class="fixed z-40 top-0 left-0 h-full w-72">

    <!-- Curved Top-Left Wave Background -->
    <div id="yellowWave" class="absolute z-[10] top-0 left-0 w-[120px] h-[120px] overflow-hidden pointer-events-none">
        <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <!-- Smooth curve from top-left to bottom-right corner -->
            <path d="M0,0 L0,100 Q60,70 70,0 Z" fill="#f1bf08" />
        </svg>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-full bg-[#007c79] text-white transform -translate-x-full transition-transform duration-500 ease-in-out shadow-2xl rounded-r-lg z-40 overflow-y-auto py-6 px-8 relative">

        <!-- Close Button on top-right inside sidebar -->
        <div id="closeSidebar" class="absolute top-4 right-4 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <div class="font-bold text-lg border-b border-white/20 pb-3 mb-4 select-none">Navigation</div>
        <ul class="space-y-3">
            <li><a href="/" class="block py-2 hover:text-yellow-300 transition">Home</a></li>
            <li><a href="/request-loop-no" class="block py-2 hover:text-yellow-300 transition">Request Loop No</a></li>
            <li><a href="/update-instrument-index" class="block py-2 hover:text-yellow-300 transition">Update Instrument Index</a></li>
            <li><a href="/view-instrument-index" class="block py-2 hover:text-yellow-300 transition">View Instrument Index</a></li>
        </ul>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm hidden"></div>
</div>
<!-- Menu Icon Positioned on Top -->
<div class="fixed top-4 left-4 z-50 cursor-pointer" id="menuToggle">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</div>

@yield('main')
<!-- Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('/js/application.js')}}"></script>

<script>
    const toggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const yellowWave = document.getElementById('yellowWave');
    const closeSidebar = document.getElementById('closeSidebar');
    const overlay = document.getElementById('overlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('open');
        yellowWave.style.display = 'none';
        toggle.style.display = 'none';
        overlay.classList.remove('hidden');
    }

    function closeSidebarFunc() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('open');
        yellowWave.style.display = 'block';
        toggle.style.display = 'block';
        overlay.classList.add('hidden');
    }

    toggle.addEventListener('click', openSidebar);

    closeSidebar.addEventListener('click', closeSidebarFunc);

    overlay.addEventListener('click', closeSidebarFunc);
</script>
<script>
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.dataset.tab;

            // Toggle active class on buttons
            tabButtons.forEach(btn => btn.classList.remove('active-tab'));
            button.classList.add('active-tab');

            // Hide/show tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(target).classList.remove('hidden');
        });
    });

</script>
</body>
</html>
