@props(['user','placeholder'=>$user,'result'=>['a','b','c']])

<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    {{--    <x-slot name="result">{{$result}}</x-slot> TODO làm V X kết quả--}}
    <x-table.body-row.user.update-full-info :user="$user" :disabled="true"/>
    <td>
        <x-button class="text-center" id='edit-button' type='button' onclick="editUser('user_{{$user['uid']}}')">
            Sửa
        </x-button>
        <x-button class="text-center hidden" id='submit-button' :form='"form_user_".$user["uid"]'>
            Gửi
        </x-button>
    </td>
    @if(Auth::user()->role==TEACHER)
        <td>
            <x-button class="text-center" :form='"form_delete_user_".$user["uid"]'
                      onclick="return cfDelUser('{{$user['fullname']}}','{{$user['uid']}}','delete_user_{{$user['uid']}}')">
                Xóa
            </x-button>
        </td>
    @endif
</x-table.row>
