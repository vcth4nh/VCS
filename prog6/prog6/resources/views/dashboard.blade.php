<x-app-layout>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.dashboard') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('fields.name') }}</th>
                            <th>{{ __('fields.phone') }}</th>
                            <th>{{ __('fields.email') }}</th>
                            @if($action ?? false)
                                <th>{{ __('fields.username') }}</th>
                                <th>{{__('fields.password')}}</th>
                                <th>{{ __('fields.update') }}</th>"
                            @elseif($action ?? false)
                                <th class='small-cell'>{{ __('fields.send-msg') }}</th>
                                <th class='small-cell'>{{ __('fields.show-msg') }}</th>
                            @endif
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
