<thead class="bg-gray-300">
<tr>
    <x-table.header-cell :header_name="__('fields.fullname')" class="max-w-[25%] min-w-[150px]"/>
    <x-table.header-cell :header_name="__('fields.phone')" class="max-w-[15%] min-w-0"/>
    <x-table.header-cell :header_name="__('fields.email')" class="max-w-[25%] min-w-0"/>
    @if(($action ?? null)==UPDATE)
        <x-table.header-cell :header_name="__('fields.username')" class="max-w-[15%] min-w-[100px]"/>
        <x-table.header-cell :header_name="__('fields.password')" class="max-w-[25%] min-w-[150px]"/>
{{--        <x-table.header-cell class="invisible"/>--}}
{{--        <x-table.header-cell class="invisible"/>--}}
{{--        @if(Auth::user()->role==TEACHER)--}}
{{--            <x-table.header-cell class="invisible"/>--}}
{{--        @endif--}}
    @endif
</tr>
</thead>
