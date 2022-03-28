@props(['user'])
<x-table.row>
    <x-slot name="id">user_{{$user['uid']}}</x-slot>
    <x-table.body-row.user.display-basic-info :user="$user"/>
    @if($user['uid']!=Auth::user()->uid)
        <x-table.body-row.cell class="p-2 w-10">
            <button class="text-center" type='submit' name='delete_user_info' value='delete_user_info'
                    onclick='sendMessage("{{$user['fullname']}}","{{$user['uid']}}")'>
                Nhắn tin
            </button>
        </x-table.body-row.cell>
        <x-table.body-row.cell class="p-2">
            <a href="{{route('msg.index').'/'.$user['uid']}}#msg-history">Xem tin</a>
        </x-table.body-row.cell>
    @endif
</x-table.row>
