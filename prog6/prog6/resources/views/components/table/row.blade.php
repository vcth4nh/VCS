@props(['result'=>''])
<tr {!! $attributes->merge(['class'=>'odd:bg-white even:bg-gray-100 hover:bg-gray-200']) !!} id="{{$id ?? ''}}">
    {{$slot}}
</tr>
