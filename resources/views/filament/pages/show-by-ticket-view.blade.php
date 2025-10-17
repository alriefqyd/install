{{-- resources/views/filament/pages/show-by-ticket-view.blade.php --}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket View</title>
    @filamentStyles
</head>
<body class="filament-body bg-gray-50 dark:bg-gray-900">

<div class="filament-main-content max-w-4xl mx-auto mt-10">
    <x-filament::card>
        <div class="text-center py-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                Ticket: {{ $ticket->first()->ticket_number ?? 'N/A' }}
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">
                This is a blank card using Filament’s theme.
            </p>
        </div>
    </x-filament::card>
</div>

@filamentScripts
</body>
</html>
