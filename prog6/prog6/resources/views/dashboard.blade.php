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

    <x-avatar/>

    <x-notification class="m-4" :success="$message ?? null"/>
    <x-page-field>
        <a href="{{ route('register') }}">Thêm người dùng mới</a>
    </x-page-field>

    <x-page-field>
        <x-table.table id="table-update" :action="UPDATE">
            @if(Auth::user()->role==TEACHER)
                <x-slot name="table_name">{{__('titles.student-list')}}</x-slot>
                @if($student_list->isNotEmpty())
                    @foreach($student_list as $student)
                        <x-table.body-row.update :user="$student"/>
                    @endforeach
                @endif
            @else
                <x-slot name="table_name">{{__('titles.personal-info')}}</x-slot>
                <x-table.body-row.update :user="$personal_info"/>
            @endif
        </x-table.table>
        <div id="delete-form">
            <form id="" method="post">
                @csrf
                @method('delete')
                <input type="hidden" name="uid" value="">
            </form>
        </div>
    </x-page-field>

    @if(Auth::user()->role==TEACHER)
        <x-page-field>
            <x-table.table :action="DISPLAY">
                <x-slot name="table_name">{{__('titles.teacher-list')}}</x-slot>
                @if($teacher_list->isNotEmpty())
                    @foreach($teacher_list as $teacher)
                        <x-table.body-row.display :user="$teacher"/>
                    @endforeach
                @endif
            </x-table.table>
        </x-page-field>
    @endif
</x-app-layout>
