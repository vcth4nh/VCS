@props(['action'])
<h1 class="text-center font-semibold text-2xl text-gray-800 leading-tight">{{$table_name}}</h1>
<div class="flex flex-col bg-[url('grid-gray-lines.jpg')]">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-6">
        <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
            <div class="flex justify-center overflow-hidden shadow-md sm:rounded-lg">
                <table
                    {!! $attributes->merge(['class'=>'min-w-fit bg-white sm:rounded-lg border-separate border border-slate-400']) !!}>
                    @if($action==MSG)
                    @else
                        <x-table.header-user :action="$action"/>
                    @endif
                    <tbody>
                    {{$slot}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
