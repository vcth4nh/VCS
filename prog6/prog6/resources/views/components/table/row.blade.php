@props(['result'=>''])
<tr {!! $attributes->merge(['class'=>'odd:bg-white even:bg-gray-100 hover:bg-gray-200']) !!} id="{{$id ?? ''}}">
    {{$slot}}
    {{--    <td class="absolute block accent-red-600 w-[60px] right-[10] text-center h-[43px]">{{$result}}</td>--}}
</tr>
