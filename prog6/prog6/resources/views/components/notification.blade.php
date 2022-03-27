@props(['errors','success'=>null])
@if ($errors->any())
    <div {{ $attributes }}>
        <div class="text-xl font-medium text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="text-lg mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($success)
    <div {{ $attributes }}>
        <div class="font-medium text-green-600">
            {{ $success }}
        </div>
    </div>
@endif
