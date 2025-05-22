<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
<div class="bg-white rounded-2xl shadow-lg p-8 max-w-lg w-full text-center">
    <div class="flex justify-center mb-6">
        <div class="bg-teal-600 p-4 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.293 9.414a1 1 0 111.414-1.414l4.243 4.243 6.364-6.364a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <h2 class="text-2xl font-semibold text-teal-800 mb-2">Submission Successful</h2>
    <p class="text-gray-700 mb-6">Your loop number request has been successfully submitted. We will review it and respond to you as soon as possible.

    </p>

    <!-- Illustration -->
    <img src="{{asset('/images/success.png')}}" alt="Illustration" class="mx-auto mb-6 w-40 h-auto">

    <a href="/">
    <button class="bg-teal-600 text-white px-6 py-2 rounded-full hover:bg-teal-700 transition">
        Back to Dashboard
    </button>
    </a>
</div>
</body>
</html>
