<nav class="navbar sticky-top navbar-expand-lg navbar-light">
	<div class="flex-row flex-wrap-no marg-none">
		<i class="fas fa-magic svg-inside"></i>
		<input class="form-control universal-filter" id="filterInput" type="text" placeholder="Filtre universel">
	</div>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
	        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto">
			@guest
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended" href="{{ route('login') }}">{{ trans('app.Login') }}</a>
				</li>
			@else
				@if(auth()->user()->role_id == (config('constants.role_admin_id')) )
					<li class="nav-item-default apply-spin">
						<a class="nav-link-extended style-journal"
						   href="{{ url('/logActivity') }}"
						   data-toggle="tooltip"
						   data-placement="bottom"
						   title="{{trans('app.logs_tool')}}"><i class="far fa-list-alt"></i></a>
					</li>
				@endif
				@if(auth()->user()->role_id == (config('constants.role_admin_id')) || auth()->user()->role_id == (config('constants.role_directeur_id')) || auth()->user()->role_id == (config('constants.role_service_id')) )
					<li class="nav-item-default apply-spin">
						<a class="nav-link-extended style-utilisateur"
						   href="{{ route('utilisateurs') }}"
						   data-toggle="tooltip"
						   data-placement="bottom"
						   title="{{trans('app.users_tool')}}"><i class="fas fa-users"></i></a>
					</li>
				@endif
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended  style-indicateur"
					   href="{{ route('indicateurs') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.dashboard_tool')}}">{{trans('app.dashboard')}}
					</a>
				</li>
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended style-activite"
					   href="{{ route('activités') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.activities_tool')}}">{{trans('app.activities')}}</a>
				</li>
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended style-tache"
					   href="{{ route('tâches') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.tasks_tool')}}">{{trans('app.tasks')}}
						<div class="badge badge-info">{{ \Illuminate\Support\Facades\Session::has('taskCount') ? \Illuminate\Support\Facades\Session::get('taskCount') : '' }}</div>
					</a>
				</li>
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended style-absence"
					   href="{{ route('absences') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.absences_tool')}}">{{trans('app.absences')}}
						<div class="badge badge-info">{{ \Illuminate\Support\Facades\Session::has('absenceCount') ? \Illuminate\Support\Facades\Session::get('absenceCount') : '' }}</div>
					</a>
				</li>
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended style-prevu"
					   href="{{ route('charges') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.charges_tool')}}">{{trans('app.charges')}}</a>
				</li>
				<li class="nav-item-default apply-spin">
					<a class="nav-link-extended style-realise"
					   href="{{ route('temps') }}"
					   data-toggle="tooltip"
					   data-placement="bottom"
					   title="{{trans('app.times_tool')}}">{{trans('app.times')}}</a>
				</li>
			@endguest
		</ul>
	</div>
</nav>
