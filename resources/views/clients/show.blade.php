<x-app-layout>
    <section class="container px-4 mx-auto">

        <form method="post" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('put')
            <section class="p-6 mb-4 bg-white rounded-md shadow-md dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">
                    Details
                </h2>

                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    <div>
                        <x-input-label :required="true" for="name" :value="__('Full Name')" />
                        <x-text-input id="name" name="name" type="text" class="block w-full mt-1"
                            :value="old('name', $client->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="text" class="block w-full mt-1"
                            :value="old('email', $client->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="phone" :value="__('Phone Number')" />
                        <x-text-input id="phone" name="phone" type="text" class="block w-full mt-1"
                            :value="old('phone', $client->phone)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="company" :value="__('Company')" />
                        <x-text-input id="company" name="company" type="text" class="block w-full mt-1"
                            :value="old('company', $client->company)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('company')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="status" :value="__('Status')" />
                        <x-select-input name="status" :options="$statusOptions" :selected="$client->status->value" />
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                </div>
            </section>

            <section class="p-6 mb-4 bg-white rounded-md shadow-md dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">
                    Address
                </h2>


                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <x-input-label :required="true" for="address_1" :value="__('Address Line 1')" />
                        <x-text-input id="address_1" name="address_1" type="text" class="block w-full mt-1"
                            :value="old('address_1', $client->address->address_1)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('address_1')" />
                    </div>

                    <div>
                        <x-input-label for="address_2" :value="__('Address Line 2')" />
                        <x-text-input id="address_2" name="address_2" type="text" class="block w-full mt-1"
                            :value="old('address_2', $client->address->address_2)" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_2')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-4">
                    <div>
                        <x-input-label :required="true" for="suburb" :value="__('Suburb')" />
                        <x-text-input id="suburb" name="suburb" type="text" class="block w-full mt-1"
                            :value="old('suburb', $client->address->suburb)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('suburb')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="postcode" :value="__('Postcode')" />
                        <x-text-input id="postcode" name="postcode" type="text" class="block w-full mt-1"
                            :value="old('postcode', $client->address->postcode)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
                    </div>

                    <div>
                        <x-input-label :required="true" for="state" :value="__('State')" />
                        <x-text-input id="state" name="state" type="text" class="block w-full mt-1"
                            :value="old('state', $client->address->state)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('state')" />
                    </div>

                    <div>
                        <x-input-label for="country" :value="__('Country')" />
                        <x-text-input id="country" name="country" type="text" class="block w-full mt-1"
                            :value="old('country', $client->address->country)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('country')" />
                    </div>
                </div>
            </section>

            <div class="flex justify-between items-center flex-wrap sm:flex-nowrap">
                @if (auth()->user()->id !== $client->id)
                    <x-danger-button x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion');">
                        {{ __('Delete User') }}
                    </x-danger-button>
                @endif
                <x-primary-button :class="auth()->user()->id === $client->id ? 'ms-auto' : ''">
                    {{ __('Save Changes') }}
                </x-primary-button>
            </div>
        </form>

    </section>

    <x-modal name="confirm-user-deletion" focusable>
        <form method="post" action="{{ route('clients.destroy', $client) }}" class="p-6">
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


</x-app-layout>
