@props(['msg_list'])
<div {!! $attributes->merge(['class'=>"border-2 border-gray-500 p-6"]) !!}>
    @if($msg_list->isNotEmpty())
        @foreach($msg_list as $msg)
            <hr>
            <p class="font-bold text-lg">{{$msg->sender->fullname}} đã nhắn lúc {{$msg['created_at']}}</p>
            @if($msg['updated_at']!=$msg['created_at'])
                <p class="text-sm text-gray-600">Chỉnh sửa lần cuối lúc {{$msg['updated_at']}}</p>
            @endif
            <p class="mt-0 mb-3">{{$msg['text']}}</p>
        @endforeach
    @else
        <p class="font-bold text-xl">Không có tin nhắn đến</p>
    @endif
</div>
