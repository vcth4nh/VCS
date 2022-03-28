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

    <x-notification class="m-4" :success="$message ?? null"/>

    <x-page-field>
        <div class="grid grid-cols-2">
            <div class="p-6  pt-0">
                <x-exercises :exer_list="$exer_list"/>
            </div>
            <x-msg-board :msg_list="$msg_list"/>
        </div>
    </x-page-field>

    <x-page-field id="upload-submit" hidden>
        <form action="{{route('submitted.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="exer_id" value="">
            <input
                class="inline-block text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                type="file" name="submitted">
            <div class="inline-block ml-4">
                <x-button>Nộp</x-button>
            </div>
        </form>
    </x-page-field>

    <x-page-field>
        <x-table.table id="table-update" :action="UPDATE">
            <x-slot name="table_name">{{__('titles.personal-info')}}</x-slot>
            <x-slot name="table_header">
                <x-table.header-user :action="UPDATE"/>
            </x-slot>
            <x-table.body-row.user.update :user="$personal_info"/>
        </x-table.table>
    </x-page-field>
</x-app-layout>
