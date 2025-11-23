<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registrationForm">
        @csrf

        <!-- Account Type Selection -->
        <div class="mb-4">
            <x-input-label for="account_type" :value="__('I am registering as:')" />
            <div class="mt-2 space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="is_family" value="0" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                           checked onchange="toggleFamilyFields()">
                    <span class="ml-2 text-sm text-gray-700">An Individual</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="is_family" value="1" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           onchange="toggleFamilyFields()">
                    <span class="ml-2 text-sm text-gray-700">A Family</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('is_family')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Your Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Family Name (shown only for families) -->
        <div id="familyFields" style="display: none;">
            <div class="mt-4">
                <x-input-label for="family_name" :value="__('Family Name')" />
                <x-text-input id="family_name" class="block mt-1 w-full" type="text" name="family_name" :value="old('family_name')" />
                <p class="mt-1 text-sm text-gray-600">e.g., "Smith Family" or "The Smiths"</p>
                <x-input-error :messages="$errors->get('family_name')" class="mt-2" />
            </div>

            <!-- Privacy Level -->
            <div class="mt-4">
                <x-input-label for="privacy_level" :value="__('Privacy Level')" />
                <select id="privacy_level" name="privacy_level" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="first_name" {{ old('privacy_level') == 'first_name' ? 'selected' : '' }}>First Name Only (e.g., "Martinez Family")</option>
                    <option value="full_name" {{ old('privacy_level') == 'full_name' ? 'selected' : '' }}>Full Name (e.g., "Martinez-Garcia Family")</option>
                    <option value="anonymous" {{ old('privacy_level') == 'anonymous' ? 'selected' : '' }}>Anonymous (e.g., "Anonymous Family")</option>
                </select>
                <p class="mt-1 text-sm text-gray-600">Controls how your name appears to others</p>
                <x-input-error :messages="$errors->get('privacy_level')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (optional) -->
        <div class="mt-4">
            <x-input-label for="contact_phone" :value="__('Phone Number (Optional)')" />
            <x-text-input id="contact_phone" class="block mt-1 w-full" type="tel" name="contact_phone" :value="old('contact_phone')" />
            <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
        </div>

        <!-- City (optional) -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City (Optional)')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('I would like to:')" />
            <div class="mt-2 space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="role" value="seeker" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                           {{ old('role') == 'seeker' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">
                        <strong>Seek Help</strong> - Post needs and receive assistance
                    </span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="role" value="helper" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           {{ old('role') == 'helper' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">
                        <strong>Provide Help</strong> - Sign up to help others in need
                    </span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="role" value="both" 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           {{ old('role') == 'both' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">
                        <strong>Both</strong> - Seek help and help others (pay it forward!)
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Bio (optional) -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('About You/Your Family (Optional)')" />
            <textarea id="bio" name="bio" rows="3" 
                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio') }}</textarea>
            <p class="mt-1 text-sm text-gray-600">Tell the community a bit about yourself</p>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleFamilyFields() {
            const isFamilyRadio = document.querySelector('input[name="is_family"]:checked');
            const familyFields = document.getElementById('familyFields');
            const familyNameInput = document.getElementById('family_name');
            
            if (isFamilyRadio && isFamilyRadio.value === '1') {
                familyFields.style.display = 'block';
                familyNameInput.required = true;
            } else {
                familyFields.style.display = 'none';
                familyNameInput.required = false;
            }
        }

        // Show family fields if coming back with validation errors
        document.addEventListener('DOMContentLoaded', function() {
            toggleFamilyFields();
        });
    </script>
</x-guest-layout>