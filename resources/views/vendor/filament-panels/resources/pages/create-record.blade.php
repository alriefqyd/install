<x-filament-panels::page
    @class([
        'fi-resource-create-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
        <div class="card mt-5 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">

            <div class="card">
                <x-filament-panels::form
                    id="form"
                    :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
                    wire:submit="create"
                >
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </x-filament-panels::form>
            </div>

        </div>
    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>

