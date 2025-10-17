<x-filament::page>
    <x-filament::section>
        <div class="space-y-6">
            {{-- Header --}}
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-primary-600">
                    Ticket: {{ $ticket_number ?? '-' }}
                </h1>

                @if($records[0]->is_finalize)
                    <x-filament::button
                        color="primary"
                        icon="heroicon-o-check-circle"
                        x-on:click="$dispatch('open-modal', { id: 'ticket-action-modal' })"
                    >
                        Take Action
                    </x-filament::button>
                @else
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-check-circle"
                        disabled
                    >
                        Take Action is disabled since the ticket is not finalized by requester.
                    </x-filament::button>
                @endif

            </div>

            {{-- Table --}}
            <div class="overflow-hidden rounded-xl shadow-sm ring-1 ring-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr class="bg-primary-600 text-white">
                        <th class="px-6 py-3 text-left text-sm font-semibold">#</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Device Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Service</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Area</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">PID Drawing</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Loop Drawing</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Created At</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($records as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->dev . '-' . $item->code }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->device_description ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->services->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->areas->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->pid_drawing ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $item->loop_drwg ?? '-' }}</td>
                            <td class="px-2 py-3">
                                <x-filament::badge :color="$item->status_updated == 'Approved' ? 'success' : 'warning'">
                                    {{ $item->status_updated ? $item->status_updated : 'Pending' }}
                                </x-filament::badge>
                            </td>
                            <td class="px-2 py-3 text-gray-700">
                                {{ $item->created_at?->format('d-M-Y h:i A') ?? '-' }}
                            </td>
                            <td class="px-6 py-3">
                                {{-- Detail Button --}}
                                <x-filament::button
                                    size="sm"
                                    color="info"
                                    icon="heroicon-o-eye"
                                    x-on:click="$dispatch('open-modal', { id: 'view-detail-{{ $item->id }}' })"
                                >
                                    View
                                </x-filament::button>

                                {{-- Modal --}}
                                <x-filament::modal id="view-detail-{{ $item->id }}" width="3xl">
                                    <x-slot name="heading">
                                        <div class="flex items-center space-x-2">
                                            <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-primary-600" />
                                            <span class="text-lg font-semibold">Device Detail</span>
                                        </div>
                                    </x-slot>

                                    <div class="bg-gray-100 rounded-lg shadow-inner p-5 space-y-4" style="padding: 10px">
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-800">

                                            {{-- Basic Info --}}
                                            <div>
                                                <span class="font-semibold text-gray-600">Code:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->dev . '-' . $item->code }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Device Description:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->device_description }}
                                                </div>
                                            </div>

                                            {{-- Related Foreign Keys --}}
                                            <div>
                                                <span class="font-semibold text-gray-600">Service:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->services->name ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Area:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->areas->name ?? '-' }}
                                                </div>
                                            </div>

                                            {{-- Drawings --}}
                                            <div>
                                                <span class="font-semibold text-gray-600">PID Drawing:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->pid_drawing }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Loop Drawing:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->loop_drwg }}
                                                </div>
                                            </div>

                                            {{-- Additional Fields --}}
                                            <div>
                                                <span class="font-semibold text-gray-600">Manufacturer:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->manufacturer ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Model:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->model ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Range Unit:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->range_unit ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Output Signal:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->outsignal ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Spec No:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->spec_no ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">PO/MR No:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->po_mr_no ?? '-' }}
                                                </div>
                                            </div>

                                            <div class="col-span-2">
                                                <span class="font-semibold text-gray-600">Remark:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm whitespace-pre-line">
                                                    {{ $item->remark ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Supply:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->supply ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Loop Number:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->loop_number ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Version:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->version ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Session ID:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->session_id ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Ticket Number:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->ticket_number ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Status Updated:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm">
                                                    {{ $item->status_updated ?? '-' }}
                                                </div>
                                            </div>

                                            <div>
                                                <span class="font-semibold text-gray-600">Remark Updated:</span>
                                                <div class="mt-1 bg-white border border-gray-200 rounded px-3 py-2 shadow-sm whitespace-pre-line">
                                                    {{ $item->remark_updated ?? '-' }}
                                                </div>
                                            </div>

                                            {{-- Dates & Status --}}
                                            <div class="col-span-2 flex items-center justify-between">
                                                <div>
                                                    <span class="font-semibold text-gray-600">Status:</span>
                                                    <x-filament::badge :color="$item->is_finalize ? 'success' : 'warning'" class="ml-2">
                                                        {{ $item->is_finalize ? 'Finalized' : 'Not Finalized' }}
                                                    </x-filament::badge>
                                                </div>
                                                <div class="flex gap-5">
                                                    <div>
                                                        <span class="font-semibold text-gray-600">Created At:</span>
                                                        <span class="ml-1 text-gray-700">
                                                            {{ $item->created_at?->format('d-M-Y h:i A') ?? '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex gap-5">
                                                    <div>
                                                        <span class="font-semibold text-gray-600">Updated At:</span>
                                                        <span class="ml-1 text-gray-700">
                                                        {{ $item->updated_at?->format('d-M-Y h:i A') ?? '-' }}
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <x-slot name="footer">
                                        <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'view-detail-{{ $item->id }}' })">
                                            Close
                                        </x-filament::button>
                                    </x-slot>
                                </x-filament::modal>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>

    {{-- ✅ Ticket Action Modal --}}
    <x-filament::modal id="ticket-action-modal" width="md" wire:key="ticket-action-modal">
        <x-slot name="heading">Ticket Action</x-slot>

        <div class="space-y-4">
            <p class="text-gray-600 text-sm">Please select an action for this ticket:</p>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <x-filament::button
                    color="{{ $actionType === 'approve' ? 'success' : 'gray' }}"
                    wire:click="$set('actionType', 'approve')"
                >
                    Approve
                </x-filament::button>

                <x-filament::button
                    color="{{ $actionType === 'reject' ? 'danger' : 'gray' }}"
                    wire:click="$set('actionType', 'reject')"
                >
                    Reject
                </x-filament::button>
            </div>

            {{-- Show rejection reason input dynamically --}}
            @if ($actionType === 'reject')
                <div class="pt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                    <x-filament::input
                        wire:model.defer="rejectionReason"
                        placeholder="Enter reason..."
                    />
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-filament::button
                    color="primary"
                    wire:click="confirmAction"
                    x-on:click="$dispatch('close-modal', { id: 'ticket-action-modal' })"
                >
                    Confirm
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    x-on:click="$dispatch('close-modal', { id: 'ticket-action-modal' })"
                >
                    Cancel
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</x-filament::page>
