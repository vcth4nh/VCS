<x-guest-layout {{ __('titles.reg') }}>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-auto h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Username -->
            <div>
                <x-label for="username" :value="__('fields.username')"/>

                <x-input id="username" class="block mt-1 w-full" type="text" name="username"
                         :value="old('username')" required
                         autofocus/>
            </div>

            <!-- Name -->
            <div class="mt-4">
                <x-label for="name" :value="__('fields.name')"/>

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                         autofocus/>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('fields.email')"/>

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required/>
            </div>

            <!-- Phone Number -->
            <div class="mt-4">
                <x-label for="phone" :value="__('fields.phone')"/>

                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('fields.password')"/>

                <x-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required autocomplete="new-password"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('fields.confirm password')"/>

                <x-input id="password_confirmation" class="block mt-1 w-full"
                         type="password"
                         name="password_confirmation" required/>
            </div>

            <!-- Role -->
            <div class="mt-4">
                <div>
                    <x-label for="role1" :value="__('fields.teacher')"/>
                    <x-input id="role1" class="mt-1" type="radio" name="role" value="teacher" required/>
                </div>

                <div>
                    <x-label for="role2" :value="__('fields.student')"/>
                    <x-input id="role2" class="mt-1" type="radio" name="role" value="student" required/>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
