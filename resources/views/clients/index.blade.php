<x-app-layout>
    <section x-data="{ selectedId: null }" class="container px-4 mx-auto">
        <div class="flex items-center flex-wrap gap-4 xs:flex-nowrap sm:justify-between">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">Clients</h2>
            @can('write user')
                <x-primary-button @click="window.location.href='{{ route('clients.create') }}'">+ Add client</x-primary-button>
            @endcan
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">

                        <x-table.table>
                            <x-slot name="tableHeader">
                                <tr>
                                    <x-table.thead>
                                        Name
                                    </x-table.thead>
                                    <x-table.thead>
                                        Email
                                    </x-table.thead>
                                    <x-table.thead>
                                        Phone
                                    </x-table.thead>
                                    <x-table.thead>
                                        Company
                                    </x-table.thead>
                                    <x-table.thead>
                                        Address
                                    </x-table.thead>
                                    <x-table.thead>
                                        Status
                                    </x-table.thead>
                                    <x-table.thead>
                                        Actions
                                    </x-table.thead>
                                </tr>
                            </x-slot>

                            <x-slot name="tableBody">
                                @foreach ($clients as $client)
                                    <tr>
                                        <x-table.tcell>
                                            {{ $client->name }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $client->email }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $client->phone }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $client->company }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $client->address?->fullAddress ?? '-' }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $client->status->label() }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            <div class="flex items-center gap-2">
                                                @can('write user')
                                                    <x-primary-button
                                                        @click="window.location.href = '{{ route('clients.show', $client) }}'">
                                                        Edit
                                                    </x-primary-button>
                                                    @if (auth()->user()->id !== $client->id)
                                                        <x-danger-button
                                                            x-on:click.prevent="selectedId = {{ $client->id }}; $dispatch('open-modal', 'confirm-user-deletion');">
                                                            {{ __('Delete User') }}
                                                        </x-danger-button>
                                                    @endif
                                                @endcan

                                            </div>
                                        </x-table.tcell>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table.table>

                    </div>
                </div>
            </div>
        </div>

        <x-modal name="confirm-user-deletion" focusable>
            <form method="post" x-bind:action="`clients/${selectedId}`" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this user?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once the client is deleted, all of its resources and data will be permanently deleted. Please click delete client button to confirm.') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button type="submit" class="ms-3">
                        {{ __('Delete Client') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <x-pagination :paginator="$clients" />
    </section>
</x-app-layout>
