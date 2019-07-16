<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>@include('includes.head')</head>

	<body>
		<main id="app">
			@include('includes.nav_bar_info')
			@include('includes.nav_bar_menu')
			<div class="main-div" id="main-div">
				@yield('content')
			</div>

			<button id="back_to_top" title="{{'Remonter'}}"><i
						class="fas fa-arrow-up svg-huge style-glowing-text"></i></button>

		</main>

	@include('includes.footer')
	@include('includes.loading')
	</body>

@include('includes.flashy_message')
@include('includes.scripts')
@include('flashy::message')
</html>
