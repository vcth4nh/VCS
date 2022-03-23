@props(['user','placeholder'=>$user,'result'=>['a','b','c']])

<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    {{--    <x-slot name="result">{{$result}}</x-slot> TODO làm V X kết quả--}}
    <x-table.body-row.user.update-full-info :user="$user" :disabled="true"/>
    <td>
        <button class="text-center" id='edit-button' type='button' onclick="editUser('{{'user_'.$user['uid']}}')">
            Sửa
        </button>
        <button class="text-center" id='submit-button' type='submit' style='display: none'
                name='update_user_info' value='update_user_info' form='{{'form_user_'.$user['uid']}}'>
            Gửi
        </button>
    </td>
    @if(Auth::user()->role==TEACHER)
        <td>
            <button class="text-center" type='submit' name='delete_user_info' value='delete_user_info'
                    onclick='return cfDel("{{$user['fullname']}}",{{$user['uid']}},"{{'delete_user_'.$user['uid']}}")'
                    form='{{'form_delete_user_'.$user['uid']}}'>
                Xóa
            </button>
        </td>
    @endif
</x-table.row>
