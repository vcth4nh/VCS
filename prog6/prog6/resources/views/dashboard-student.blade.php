<x-app-layout>
    <x-slot name="style">
        <style>
            #table-update td:nth-last-child({{Auth::user()->role==TEACHER ? 3 : 2}}) {
                position: relative;
            }
        </style>
    </x-slot>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.dashboard') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.dashboard') }}
        </h2>
    </x-slot>

    <x-page-field>
        <div class="grid grid-cols-2">
            <div class="p-6">
                <x-table.table>
                    <x-slot name="table_name">{{__('titles.exercises')}}</x-slot>
                    <x-table.header-exer></x-table.header-exer>
                </x-table.table>
            </div>
            <x-msg-board :msg_list="$msg_list"/>
        </div>
    </x-page-field>

    <x-notification class="m-4" :success="$message ?? null"/>
    <x-page-field>
        <x-table.table id="table-update" :action="UPDATE">
            <x-slot name="table_name">{{__('titles.personal-info')}}</x-slot>
            <x-table.body-row.user.update :user="$personal_info"/>
        </x-table.table>
    </x-page-field>
</x-app-layout>
