<x-app-layout>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.user-list') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.user-list') }}
        </h2>
    </x-slot>

    <x-page-field>
        <x-table.table :action="DISPLAY">
            <x-slot name="table_name">{{__('titles.student-list')}}</x-slot>
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

    <x-page-field id="send-msg" class="hidden">
        <form method="post" action="{{ route('msg.store') }}">
            <input type="hidden" name="recv_uid" value="">
            @csrf
            <label>
                <span id="send-to"></span><br>
                <textarea name="text"></textarea>
            </label>
            <br>
            <button type="submit">Gửi</button>
        </form>
    </x-page-field>

    @if(isset($msg_list) && $msg_list->isNotEmpty())
        <x-page-field id="msg-history">
            @foreach($msg_list as $msg)
                <div>
                    <hr>
                    <p class="font-bold text-lg">Gửi đến {{$msg->recver->fullname}} lúc {{$msg['created_at']}}</p>
                    @if($msg['updated_at']!=$msg['created_at'])
                        <p class="text-sm text-gray-600">Chỉnh sửa lần cuối lúc {{$msg['updated_at']}}</p>
                    @endif
                    <form method="post" id="msg_{{$msg['msg_id']}}" action="{{ route('msg.update') }}">
                        @csrf
                        @method('put')
                        <textarea name="text" class="disabled:opacity-75" disabled>{{$msg['text']}}</textarea><br>
                        <x-button type="button" id="edit-button" :onclick="'editMgs(\''.$msg['msg_id'].'\')'">
                            Sửa
                        </x-button>
                        <x-button id="submit-button" name="msg_id" :value="$msg['msg_id']"
                                  class="hidden">
                            Gửi
                        </x-button>
                        <x-button name="msg_id" :value="$msg['msg_id']"
                                  :onclick="'return confirm(\'Xác nhận xóa tin nhắn\')'" form="delete-msg">
                            Xóa
                        </x-button>
                    </form>
                    <form id="delete-msg" action="{{route('msg.destroy')}}" method="post">
                        @csrf
                        @method('delete')
                    </form>
                </div>
            @endforeach
        </x-page-field>
    @endif

</x-app-layout>
