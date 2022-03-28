<thead class="bg-gray-300">
<tr>
    <x-table.header-cell :header_name="__('fields.exer-name')"/>
    <x-table.header-cell class="w-[20%]" :header_name="__('fields.created-at')"/>
    <x-table.header-cell class="w-[15%]" :header_name="__('fields.download')"/>
    @if(Auth::user()->role==TEACHER)
        <x-table.header-cell class="w-[6%]" :header_name="__('fields.detail')"/>
        <x-table.header-cell class="w-[6%]" :header_name="__('fields.delete')"/>
    @else
        <x-table.header-cell class="w-[15%]" :header_name="__('fields.upload')"/>
    @endif
</tr>
</thead>
