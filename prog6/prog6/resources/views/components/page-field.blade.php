<div {!! $attributes->merge(['class'=>'py-3']) !!}>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
