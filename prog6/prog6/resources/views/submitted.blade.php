<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
<h1 class="text-center font-semibold text-2xl text-gray-800 leading-tight">{{__('titles.submitted')}} của bài tập {{$original_name}}</h1>
<table class="min-w-full bg-white sm:rounded-lg border-separate border border-slate-400">
    <x-table.header-submitted/>
    <tbody>
    @foreach($submitted_list as $submitted)
        <x-table.body-row.submitted :submitted="$submitted"/>
    @endforeach
    </tbody>
</table>
</body>
</html>
