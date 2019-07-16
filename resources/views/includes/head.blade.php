<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}/{{ucfirst(Request::route()->getName())}}</title>

{{--STYLES--}}
{{--<link rel="stylesheet" href="{{ asset('bootstrap-sortable/Contents/bootstrap-sortable.css') }}">--}}

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
      integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

{{--<link rel="stylesheet" href="{{ asset('css/app.css') }}">--}}
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/flashy.css') }}">
<link rel="stylesheet" href="{{ asset('css/dragdrop.css') }}">

@if(session()->get('theme') == 1)
	<link rel="stylesheet" href="{{ asset('css/theme_01.css') }}">
@elseif(session()->get('theme') == 2)
	<link rel="stylesheet" href="{{ asset('css/theme_02.css') }}">
@elseif(session()->get('theme') == 3)
	<link rel="stylesheet" href="{{ asset('css/theme_03.css') }}">
@endif

@if(session()->get('zoom') == config('constants.zoom_75'))
	<link rel="stylesheet" href="{{ asset('css/zoom_75.css') }}">
@elseif(session()->get('zoom') == config('constants.zoom_100'))
	<link rel="stylesheet" href="{{ asset('css/zoom_100.css') }}">
@elseif(session()->get('zoom') == config('constants.zoom_125'))
	<link rel="stylesheet" href="{{ asset('css/zoom_125.css') }}">
@endif

<!--<link href="https://fonts.googleapis.com/css?family=Lato|Roboto" rel="stylesheet">-->
<!-- <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet"> -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

{{--SCRIPTS--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="{{ asset('js/app.js') }}" defer></script>
{{--<script src="https://kit.fontawesome.com/4d3b8c129c.js"></script>--}}
<script src="{{ asset('js/main.js') }}" defer></script>
<script src="{{ asset('js/idleTimer.min.js') }}" defer></script>
{{--<script rel="script" src="{{ asset('fontAwsome/js/all.min.js') }}"></script>--}}

{{--<script rel="script" src="{{ asset('bootstrap-sortable/Scripts/bootstrap-sortable.js') }}"></script>--}}
{{--<script rel="script" src="{{ asset('bootstrap-sortable/Scripts/moment.min.js') }}"></script>--}}

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
