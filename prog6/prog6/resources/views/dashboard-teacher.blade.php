<x-app-layout>
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
                <div class="mb-3">
                    <p class="text-lg font-bold text-center">Upload bài tập mới</p>
                    <div class="flex justify-center">
                        <form method="post" action="{{route('exercises.store')}}" enctype="multipart/form-data">
                            @csrf
                            <input
                                class="inline-block text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                type="file" name="exer">
                            <div class="inline-block ml-4">
                                <x-button>Đăng</x-button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr><br>
                <x-exercises :exer_list="$exer_list"/>
            </div>
            <x-msg-board :msg_list="$msg_list"/>
        </div>

        <div id="delete-exer">
            <form id="" action="{{route('exercises.destroy')}}" method="post">
                @csrf
                @method('delete')
                <input type="hidden" name="exer_id" value="">
            </form>
        </div>
    </x-page-field>

    <div id="submitted-list" class='w-full h-max' hidden>
        <x-page-field class="">
            <iframe src="" class="w-full h-fit "></iframe>
        </x-page-field>
    </div>

    <x-page-field class="text-center text-2xl">
        <a href="{{ route('register') }}" class="bg-gray-300 border-gray-400 border-2 rounded-lg p-3">
            Thêm người dùng mới</a>
    </x-page-field>

    <x-page-field>
        <x-table.table id="table-update" :action="UPDATE">
            <x-slot name="table_name">{{__('titles.student-list')}}</x-slot>
            <x-slot name="table_header">
                <x-table.header-user :action="UPDATE"/>
            </x-slot>
            @if($student_list->isNotEmpty())
                @foreach($student_list as $student)
                    <x-table.body-row.user.update :user="$student"/>
                @endforeach
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

    <x-page-field>
        <x-table.table :action="DISPLAY">
            <x-slot name="table_name">{{__('titles.teacher-list')}}</x-slot>
            <x-slot name="table_header">
                <x-table.header-user :action="DISPLAY"/>
            </x-slot>
            @if($teacher_list->isNotEmpty())
                @foreach($teacher_list as $teacher)
                    <x-table.body-row.user.display :user="$teacher"/>
                @endforeach
            @endif
        </x-table.table>
    </x-page-field>
</x-app-layout>
