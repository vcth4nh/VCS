@props(['user','result'=>['a','b','c']])
<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    {{--    <x-slot name="result">{{$result}}</x-slot> TODO làm V X kết quả--}}
    <x-table.body-row.display-basic-info :user="$user"/>
    <td>
        <button class="text-center" type='submit' name='delete_user_info' value='delete_user_info'
                onclick='return cfDel("{{$user['fullname']}}","{{'form_user_'.$user['uid']}}")'
                form='{{'form_user_'.$user['uid']}}'>
            Nhắn tin
        </button>
    </td>
    <td>
        <button class="text-center" type='submit' name='delete_user_info' value='delete_user_info'
                onclick='return cfDel("{{$user['fullname']}}","{{'form_user_'.$user['uid']}}")'
                form='{{'form_user_'.$user['uid']}}'>
            Xem tin
        </button>
    </td>
</x-table.row>
