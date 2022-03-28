@props(['user'])
<x-table.body-row.cell>{{$user['fullname']}}</x-table.body-row.cell>
<x-table.body-row.cell class="w-[20%]">{{$user['phone']}}</x-table.body-row.cell>
<x-table.body-row.cell class="w-[25%]">{{$user['email']}}</x-table.body-row.cell>
