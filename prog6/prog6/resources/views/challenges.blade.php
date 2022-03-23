<x-app-layout>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.challenges') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.challenges') }}
        </h2>
    </x-slot>
</x-app-layout>
