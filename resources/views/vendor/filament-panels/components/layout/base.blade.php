@props([
    'livewire' => null,
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
    <head>
        <style>
            /* === Vale Sidebar Custom Theme === */

            .fi-btn:hover {
                background-color: #fdbe00 !important;
            }
            /* Base sidebar */
            .fi-sidebar {
                background-color: #ffffff !important;
                border-right: 1px solid #e5e7eb !important;
                color: #066B61 !important;
            }

            /* Sidebar items */
            .fi-sidebar .fi-sidebar-item {
                color: #066B61 !important;
                background-color: transparent !important;
                border-radius: 10px;
                margin: 2px 8px;
                transition: all 0.2s ease-in-out;
                position: relative;
            }

            /* Remove Filament's default hover layer */
            .fi-sidebar .fi-sidebar-item:before,
            .fi-sidebar .fi-sidebar-item:after {
                content: none !important;
                background: none !important;
            }

            /* Hover (soft highlight) */
            .fi-sidebar .fi-sidebar-item a:hover {
                background-color: #fdbe00 !important; /* soft green tint */
                color: #05584E !important;
            }

            /* When hovering the link, make the label white */
            .fi-sidebar .fi-sidebar-item a:hover .fi-sidebar-item-label {
                color: #ffffff !important;
            }

            /* Optional: also make icon white when hovered */
            .fi-sidebar .fi-sidebar-item a:hover svg {
                color: #ffffff !important;
            }

            /* Active (Vale Yellow + bold) */
            .fi-sidebar .fi-sidebar-item-active {
                background-color: #F9E545 !important; /* Vale yellow */
                color: #066B61 !important;
                font-weight: 600;
            }

            /* Add subtle left border to active item */
            .fi-sidebar .fi-sidebar-item-active::before {
                content: "";
                position: absolute;
                left: 0;
                top: 6px;
                bottom: 6px;
                width: 4px;
                border-radius: 0 3px 3px 0;
                background-color: #066B61; /* Vale green */
            }

            /* Icons default */
            .fi-sidebar .fi-sidebar-item svg {
                color: #066B61 !important;
                transition: color 0.2s ease-in-out;
            }

            /* Hover icon */
            .fi-sidebar .fi-sidebar-item:hover svg {
                color: #05584E !important;
            }

            /* Active icon */
            .fi-sidebar .fi-sidebar-item-active svg {
                color: #066B61 !important;
            }

            .fi-sidebar-item-active a {
                background-color: #fdbe00 !important;
            }

            .fi-sidebar-item-active a .fi-sidebar-item-label {
                color: #ffffff !important;
            }

            .fi-sidebar .fi-sidebar-item-active a svg {
                color: #ffffff !important;
            }



            /* Topbar match Vale theme */
            .fi-topbar {
                background-color: #E6C400 !important; /* Vale yellow */
                /*margin-bottom: 30px;*/
            }

            .fi-topbar button,
            .fi-topbar a {
                color: #066B61 !important;
            }

            .fi-ta-table tbody tr:nth-child(odd) {
                background-color: #f9fafb; /* light gray */
            }

            .fi-ta-table tbody tr:nth-child(even) {
                background-color: #ffffff; /* white */
            }

            /* Optional: add hover color */
            .fi-ta-table tbody tr:hover {
                background-color: #fff8e1; /* soft yellow hover */
            }

            .fi-ta-header-cell, thead .fi-ta-selection-cell, .fi-ta-actions-header-cell {
                background-color: #009199 !important;
            }

            .fi-ta-header-cell .fi-ta-header-cell-label, .fi-ta-header-cell-sort-icon {
                color: white !important;
            }

        </style>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_START, scopes: $livewire->getRenderHookScopes()) }}

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @if ($favicon = filament()->getFavicon())
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        @php
            $title = trim(strip_tags(($livewire ?? null)?->getTitle() ?? ''));
            $brandName = trim(strip_tags(filament()->getBrandName()));
        @endphp

        <title>
            {{ filled($title) ? "{$title} - " : null }} {{ $brandName }}
        </title>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>

        @filamentStyles

        {{ filament()->getTheme()->getHtml() }}
        {{ filament()->getFontHtml() }}

        <style>
            :root {
                --font-family: '{!! filament()->getFontFamily() !!}';
                --sidebar-width: {{ filament()->getSidebarWidth() }};
                --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
                --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
            }
        </style>

        @stack('styles')

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_AFTER, scopes: $livewire->getRenderHookScopes()) }}

        @if (! filament()->hasDarkMode())
            <script>
                localStorage.setItem('theme', 'light')
            </script>
        @elseif (filament()->hasDarkModeForced())
            <script>
                localStorage.setItem('theme', 'dark')
            </script>
        @else
            <script>
                const loadDarkMode = () => {
                    window.theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

                    if (
                        window.theme === 'dark' ||
                        (window.theme === 'system' &&
                            window.matchMedia('(prefers-color-scheme: dark)')
                                .matches)
                    ) {
                        document.documentElement.classList.add('dark')
                    }
                }

                loadDarkMode()

                document.addEventListener('livewire:navigated', loadDarkMode)
            </script>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_END, scopes: $livewire->getRenderHookScopes()) }}
    </head>

    <body
        {{ $attributes
                ->merge(($livewire ?? null)?->getExtraBodyAttributes() ?? [], escape: false)
                ->class([
                    'fi-body',
                    'fi-panel-' . filament()->getId(),
                    'min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white',
                ]) }}
    >
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_START, scopes: $livewire->getRenderHookScopes()) }}


        {{ $slot }}

        @livewire(Filament\Livewire\Notifications::class)

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

        @filamentScripts(withCore: true)

        @if (filament()->hasBroadcasting() && config('filament.broadcasting.echo'))
            <script data-navigate-once>
                window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            </script>
        @endif

        @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
            <script>
                loadDarkMode()
            </script>
        @endif

        @stack('scripts')

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER, scopes: $livewire->getRenderHookScopes()) }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_END, scopes: $livewire->getRenderHookScopes()) }}
    </body>
</html>
