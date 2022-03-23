<x-app-layout>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.user-list') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.user-list') }}
        </h2>
    </x-slot>

    <x-page-field>
        <x-table.table :action="DISPLAY">
            <x-slot name="table_name">{{__('titles.teacher-list')}}</x-slot>
            @if($student_list->isNotEmpty())
                @foreach($student_list as $student)
                    <x-table.body-row.message :user="$student"/>
                @endforeach
            @endif
        </x-table.table>
    </x-page-field>
    <x-page-field>
        <x-table.table :action="DISPLAY">
            <x-slot name="table_name">{{__('titles.teacher-list')}}</x-slot>
            @if($teacher_list->isNotEmpty())
                @foreach($teacher_list as $teacher)
                    <x-table.body-row.message :user="$teacher"/>
                @endforeach
            @endif
        </x-table.table>
    </x-page-field>
</x-app-layout>
