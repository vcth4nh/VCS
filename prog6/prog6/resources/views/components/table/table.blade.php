<h1 class="text-center font-semibold text-2xl text-gray-800 leading-tight">{{$table_name ?? null}}</h1>
<div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-6">
        <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
            <div class="flex justify-center overflow-hidden shadow-md sm:rounded-lg">
                <table
                    {!! $attributes->merge(['class'=>'min-w-full bg-white sm:rounded-lg border-separate border border-slate-400']) !!}>
                    {{ $table_header }}
                    <tbody>
                    {{$slot}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
