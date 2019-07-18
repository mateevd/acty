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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/af-2.3.3/b-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

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
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" defer ></script>--}}
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/af-2.3.3/b-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js" defer ></script>

<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" defer ></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer ></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" defer ></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer ></script>
{{--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" defer ></script>--}}
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" defer ></script>


{{--<script src="{{ asset('js/app.js') }}" defer ></script>--}}
<script src="{{ asset('js/main.js') }}" defer ></script>
<script src="{{ asset('js/idleTimer.min.js') }}" defer ></script>


{{--<script rel="script" src="{{ asset('fontAwsome/js/all.min.js') }}"></script>--}}
{{--<script rel="script" src="{{ asset('bootstrap-sortable/Scripts/bootstrap-sortable.js') }}"></script>--}}
{{--<script rel="script" src="{{ asset('bootstrap-sortable/Scripts/moment.min.js') }}"></script>--}}

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
