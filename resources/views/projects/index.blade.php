<x-app-layout>
    <section x-data="{ selectedId: null }" class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center gap-4 xs:flex-nowrap sm:justify-between">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">Projects</h2>
            @can('write user')
                <x-primary-button @click="window.location.href='{{ route('projects.create') }}'">+ Add
                    project</x-primary-button>
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
                                        Status
                                    </x-table.thead>
                                    <x-table.thead>
                                        Start Date
                                    </x-table.thead>
                                    <x-table.thead>
                                        End Date
                                    </x-table.thead>
                                    <x-table.thead>
                                        Budget
                                    </x-table.thead>
                                    <x-table.thead>
                                        Updated At
                                    </x-table.thead>
                                    <x-table.thead>
                                        Created By
                                    </x-table.thead>
                                    <x-table.thead>
                                        Actions
                                    </x-table.thead>
                                </tr>
                            </x-slot>

                            <x-slot name="tableBody">
                                @foreach ($projects as $project)
                                    <tr>
                                        <x-table.tcell>
                                            {{ $project->name }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $project->status->label() }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $project->start_date->format('d/m/Y') }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $project->end_date->format('d/m/Y') }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ number_format($project->budget, 2) }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $project->updated_at->format('d/m/Y H:i a') }}
                                        </x-table.tcell>

                                        <x-table.tcell>
                                            {{ $project->createdBy?->name ?: '-' }}
                                        </x-table.tcell>


                                        <x-table.tcell>
                                            <div class="flex items-center gap-2">
                                                @can('write user')
                                                    <x-primary-button
                                                        @click="window.location.href = '{{ route('projects.show', $project) }}'">
                                                        Edit
                                                    </x-primary-button>
                                                    @if (auth()->user()->id !== $project->id)
                                                        <x-danger-button
                                                            x-on:click.prevent="selectedId = {{ $project->id }}; $dispatch('open-modal', 'confirm-user-deletion');">
                                                            {{ __('Delete Project') }}
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
            <form method="post" x-bind:action="`projects/${selectedId}`" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this project?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once the project is deleted, all of its resources and data will be permanently deleted. Please click delete project button to confirm.') }}
                </p>

                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button type="submit" class="ms-3">
                        {{ __('Delete Project') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <x-pagination :paginator="$projects" />
    </section>
</x-app-layout>
