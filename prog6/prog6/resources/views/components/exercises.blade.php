@props(['exer_list'])

<x-table.table>
    <x-slot name="table_name">{{__('titles.exercises')}}</x-slot>
    <x-slot name="table_header">
        <x-table.header-exer/>
    </x-slot>
    @foreach($exer_list as $exer)
        <x-table.row>
            <x-slot name="id">exer_{{$exer['exer_id']}}</x-slot>
            <x-table.body-row.cell>{{$exer['original_name']}}</x-table.body-row.cell>
            <x-table.body-row.cell>{{$exer['created_at']}}</x-table.body-row.cell>
            <x-table.body-row.cell class="text-center">
                <a href="{{route('exercises.download',$exer['location'])}}" class=" text-blue-600">Tải
                    xuống</a>
            </x-table.body-row.cell>
            @if(Auth::user()->role==TEACHER)
                <x-table.body-row.cell>
                    <div class="flex justify-center">
                        <x-button onclick="showSubmitted('{{route('submitted.index',$exer['exer_id'])}}')">Xem
                        </x-button>
                    </div>
                </x-table.body-row.cell>
                <x-table.body-row.cell>
                    <div class="flex justify-center">
                        <x-button
                            onclick="return cfDelExer('{{$exer['original_name']}}','{{$exer['exer_id']}}','exer_{{$exer['exer_id']}}')"
                            class="mx-auto" form="form_exer_{{$exer['exer_id']}}">
                            Xóa
                        </x-button>
                    </div>
                </x-table.body-row.cell>
            @else
                <x-table.body-row.cell>
                    <div class="flex justify-center">
                        <x-button onclick="showUpload('{{$exer['exer_id']}}')">Nộp bài</x-button>
                    </div>
                </x-table.body-row.cell>
            @endif
        </x-table.row>
    @endforeach
</x-table.table>
