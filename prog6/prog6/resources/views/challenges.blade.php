<x-app-layout>
    <x-slot name="title">{{ config('app.name') }} | {{ __('titles.challenges') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('navigation.challenges') }}
        </h2>
    </x-slot>

    @if(Auth::user()->role==TEACHER)
        <x-page-field class="inline-block">
            <p class="error"></p>
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <p class="mb-0"><b>Đăng challenge mới</b></p>
                <input
                    class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    type="file" name="chall">
                <textarea spellcheck="false" name="hint" placeholder="Nhập hint cho challenge"></textarea>
                <br>
                <x-button>Đăng</x-button>
            </form>
        </x-page-field>
        <div class="inline-block">
            <x-notification/>
        </div>
    @endif

    @if(array_key_exists('content', get_defined_vars()) === true)
        <x-page-field class="inline-block">
            @if($content!=null)
                <p class="text-xl text-green-600">Đáp án đúng</p>
                <p class="text-xl">Nội dung file</p>
                <p class="text-lg">{{$content}}</p>
            @else
                <p class="text-xl text-red-600">Đáp án sai</p>
            @endif
        </x-page-field>
    @endif


    @if(isset($chall_list) && $chall_list->isNotEmpty())
        <x-page-field>
            <div class="grid grid-cols-4">
                @php($count=0)
                @foreach($chall_list as $chall)
                    @php($count++)
                    <div class="inline-block border-2 shadow-lg p-3">
                        <h3 id="chall_{{$chall['chall_id']}}" class="text-xl font-bold"></h3>
                        <form action="{{route('challenges.check')}}" method="post">
                            @csrf
                            <p class="text-lg"> Challenge {{$count}}</p>
                            <p class="text-sm">Thời gian đăng: {{$chall['created_at']}}</p>
                            <p class="text-lg">Gợi ý: {{$chall['hint']}}</p>
                            <label>
                                <p></p>
                                <textarea name="answer"></textarea>
                            </label>
                            <x-button name="chall_id" value="{{$chall['chall_id']}}">Nộp</x-button>
                        </form>
                    </div>
                @endforeach
            </div>
        </x-page-field>
    @endif
</x-app-layout>
