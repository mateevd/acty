<nav class="navbar navbar-expand-lg navbar-dark flex-row justify-between items-center">

	<div class="flex-row justify-flex-start items-center pad-h-right-small">
		<div class="logo-container @if(!auth()->check()) bg-clair-ultra @endif">
			<img class="custom-infos-brand-logo"
				src="{{ URL::to('/logos/'. \Illuminate\Support\Facades\Session::get('app_logo') ) }}">
		</div>
		<div class="custom-infos-brand wrap-no pad-v-small">
			{{ \Illuminate\Support\Facades\Session::get('app_name') }}
		</div>
	</div>

	<div class="flex-row justify-center items-center custom-infos-page flex-wrap-no wrap-no pad-h-small pad-v-small">
		<span class="pad-h-left-small">{{ucfirst(Request::route()->getName())}}</span>
	</div>

	<div class="flex-col pad-all-small">

		<div class="flex-row justify-flex-end items-center wrap-no custom-infos-users apply-spin">
			<span class="">
				@if(auth()->check())
					{{session()->get('user_role')}}>{{session()->get('user_tri')}}>{{session()->get('user_service')}}
					>{{session()->get('user_department')}}
				@else
					Connexion à {{ \Illuminate\Support\Facades\Session::get('app_name') }}
				@endif
			</span>

			@if(auth()->check())
				<a class="nav-link-navbar-dark marg-h-left-small"
					href="{{ url('/info') }}"
					data-toggle="tooltip"
					data-placement="bottom"
					title="{{trans('app.info_tool')}}">
					 <i class="fas fa-question-circle svg-tiny btn-common marg-none"></i></a>
					 
				<a class="nav-link-navbar-dark marg-h-left-small"
				   href="{{ route('Configuration') }}"
				   data-toggle="tooltip"
				   data-placement="bottom"
				   title="{{trans('users.lab_settings')}}">
					<i class="fas fa-cog svg-tiny btn-common marg-none"></i></a>

				<a class="nav-link-navbar-dark marg-h-left-small"
				   href="{{ route('logout') }}"
				   data-toggle="tooltip"
				   data-placement="bottom"
				   title="{{trans('app.logout_tool')}}"
				   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					<i class="fas fa-sign-out-alt svg-tiny btn-common marg-none"></i></a>

				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hide-submit">
					@csrf
				</form>
			@endif
		</div>

		<div class="flex-row justify-flex-end items-center wrap-no style-date-du-jour">
			<span class="pad-h-only-small">{{ucfirst(session()->get('month_name'))}}</span>
			<span class="">{{session()->get('current_year')}}</span>
		</div>
	</div>
</nav>

@include('includes.config_users_modal')
