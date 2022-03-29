@props(['user','placeholder'=>$user,'result'=>['a','b','c']])

<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    <x-table.body-row.user.update-full-info :user="$user" :disabled="true"/>
    <td class="w-[6%]">
        <div class="flex justify-center">
            <x-button class="text-center" id='edit-button' type='button'
                      onclick="editUser('user_{{$user['uid']}}','{{Auth::user()->role}}')">
                Sửa
            </x-button>
            <x-button class="text-center hidden" id='submit-button' :form='"form_user_".$user["uid"]'>
                Gửi
            </x-button>
        </div>
    </td>
    @if(Auth::user()->role==TEACHER)
        <td class="w-[6%]">
            <div class="flex justify-center">
                <x-button class="text-center" :form='"form_delete_user_".$user["uid"]'
                          onclick="return cfDelUser('{{$user['fullname']}}','{{$user['uid']}}','delete_user_{{$user['uid']}}')">
                    Xóa
                </x-button>
            </div>
        </td>
    @endif
</x-table.row>
