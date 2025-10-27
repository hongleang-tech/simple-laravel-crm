<x-app-layout>
    <section x-data="{ selectedUserId: null }" class="container px-4 mx-auto">
        <h2 class="text-lg font-medium text-gray-800 dark:text-white">Users</h2>

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
                                        Role
                                    </x-table.thead>
                                    <x-table.thead>
                                        Address
                                    </x-table.thead>
                                    <x-table.thead>
                                        Phone
                                    </x-table.thead>
                                    <x-table.thead>
                                        Actions
                                    </x-table.thead>
                                </tr>
                            </x-slot>

                            <x-slot name="tableBody">
                                @foreach ($users as $user)
                                    <tr>
                                        <x-table.tcell>
                                            {{ $user->name }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $user->email }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $user->roleNames }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $user->address->fullAddress }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $user->phone_number }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            <div class="flex items-center gap-2">
                                                @if (auth()->user()->hasRole('admin'))
                                                    <x-primary-button
                                                        @click="window.location.href = '{{ route('users.show', $user) }}'">
                                                        Edit
                                                    </x-primary-button>
                                                    @if (auth()->user()->id !== $user->id)
                                                        <x-danger-button
                                                            x-on:click.prevent="selectedUserId = {{ $user->id }}; $dispatch('open-modal', 'confirm-user-deletion');">
                                                            {{ __('Delete User') }}
                                                        </x-danger-button>
                                                    @endif
                                                @endif

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
            <form method="post" x-bind:action="`users/${selectedUserId}`" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this user?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once the user is deleted, all of its resources and data will be permanently deleted. Please click delete user button to confirm.') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button type="submit" class="ms-3">
                        {{ __('Delete User') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <x-pagination :paginator="$users" />
    </section>
</x-app-layout>
