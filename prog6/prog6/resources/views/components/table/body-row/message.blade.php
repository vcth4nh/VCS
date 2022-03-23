@props(['user','result'=>['a','b','c']])
<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    <x-table.body-row.user.display-basic-info :user="$user"/>
    @if($user['uid']!=Auth::user()->uid)
        <td>
            <button class="text-center" type='submit' name='delete_user_info' value='delete_user_info'
                    onclick='sendMessage("{{$user['fullname']}}","{{$user['uid']}}")'>
                Nhắn tin
            </button>
        </td>
        <td>
            <a href="{{route('msg.index').'/'.$user['uid']}}#msg-history">Xem tin</a>
        </td>
    @endif
</x-table.row>
