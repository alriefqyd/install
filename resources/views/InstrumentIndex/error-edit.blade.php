<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

<div class="bg-white rounded-2xl shadow-lg p-8 max-w-lg w-full text-center">
    <div class="flex justify-center mb-6">
        <div class="bg-red-600 p-4 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-8 w-8 text-white"
                 viewBox="0 0 20 20"
                 fill="currentColor">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.25a.75.75 0 111.5 0 .75.75 0 01-1.5 0zM9.25 6a.75.75 0 011.5 0v5a.75.75 0 01-1.5 0V6z"
                      clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    <h2 class="text-2xl font-semibold text-red-800 mb-2">Warning</h2>

    <p class="text-gray-700 mb-6">
        {{-- Default fallback message --}}
        {{ $message}}
    </p>

    {{-- Optional image --}}
    {{-- <img src="{{ asset('/images/error.png') }}" alt="Error Illustration" class="mx-auto mb-6 w-40 h-auto"> --}}

    <a href="/request-loop-no">
        <button class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition">
            Back to Form
        </button>
    </a>
</div>

</body>
</html>
