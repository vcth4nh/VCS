@props(['submitted'])
<x-table.row>
    <x-table.body-row.cell>{{$submitted->user->fullname}}</x-table.body-row.cell>
    <x-table.body-row.cell>{{$submitted['created_at']}}</x-table.body-row.cell>
    <x-table.body-row.cell>
        <a href="{{route('submitted.download',$submitted['location'])}}" class="text-center">Tải xuống</a>
    </x-table.body-row.cell>
</x-table.row>
