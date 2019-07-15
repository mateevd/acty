<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>@include('includes.head')</head>

	<body>
		<main id="app">
			@include('includes.nav_bar_info')
			<div class="main-div">
				@yield('content')
			</div>
		</main>
	
	@include('includes.footer')
	@include('includes.loading')
	</body>

@include('includes.scripts')

</html>
