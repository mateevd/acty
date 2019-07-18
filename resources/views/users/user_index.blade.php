@extends('layouts.app')
@section('content')

@if(Session::has('flashy_notification.message'))
	<script id="flashy-template" type="text/template">
		<div class="flashy flashy--{{ Session::get('flashy_notification.type') }}">
			<i class="flashy__body"></i>
		</div>
	</script>
@endif

{{--SELECT PERIOD--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="users-tab-selection">
			@if(auth()->user()->roles->name == (config('constants.role_admin')) ||
			auth()->user()->roles->name == (config('constants.role_directeur')) ||
			auth()->user()->roles->name == (config('constants.role_service')))
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#users_list" role="tab"
					   data-toggle="tab">
						<div>
							@if(auth()->user()->roles->name == (config('constants.role_service'))) {{ucfirst(trans('app.service'))}} @endif
							@if(auth()->user()->roles->name == (config('constants.role_directeur'))) {{ucfirst(trans('app.department'))}} @endif
							@if(auth()->user()->roles->name == (config('constants.role_admin'))) {{ucfirst(trans('app.department'))}} @endif
						</div>
					</a>
				</li>
			@endif
		</ul>
	</div>
	@include('includes.date_select')
</div>

{{--<div class="tab-content">--}}
	{{--<div class="tab-pane" role="tabpanel" id="users_list">--}}
		{{--<div class="table-responsive">--}}
			<table class="table" id="example">
				<thead>
				<tr>
					{{--<th data-defaultsort="disabled" class="text-center">--}}

						{{--<div class="action-btn-user">--}}
							{{--CREATE USER--}}
							{{--<button class="btn-common " id="createUserButton"--}}
							        {{--title="{{trans('app.add')}}"--}}
							        {{--data-toggle="modal"--}}
							        {{--data-toggle="tooltip"--}}
							        {{--data-placement="bottom"--}}
							        {{--data-target="#createUser">--}}
								{{--<i class="fas fa-plus svg-large btn-theme-fonce-leger"></i>--}}
							{{--</button>--}}
						{{--</div>--}}
					{{--</th>--}}
					<th data-defaultsort="asc">{{trans('users.Lname')}}</th>
					<th>{{trans('users.Fname')}}</th>
					<th>{{trans('users.Login')}}</th>
					<th>{{trans('users.Role')}}</th>
					<th>{{trans('users.EmailAddress')}}</th>
{{--					@if(auth()->user()->roles->name == (config('constants.role_admin')))--}}
						<th class="text-center tiny-cell">{{trans('users.Status')}}</th>
					{{--@endif--}}
					<th class="text-center tiny-cell">{{trans('users.Trigram')}}</th>
					<th class="text-center tiny-cell">{{trans('users.Department')}}</th>
					<th class="text-center tiny-cell">{{trans('users.Service')}}</th>
					@if(session()->get('budget_option') == 1)
						<th class="text-right tiny-cell">{{trans('users.Daily')}}</th>
					@endif
				</tr>

				</thead>
				<tbody id="filter_table">
				{{--@foreach($users as $user)--}}
					{{--<tr>--}}
						{{--<td class="action-btn-item">--}}
							{{--<div class="action-btn-group">--}}

								{{--EDIT USER--}}
								{{--<button class="btn-common " id="editUserButton"--}}
								        {{--title="{{ucfirst(trans('app.Edit'))}}"--}}
								        {{--data-toggle="modal"--}}
								        {{--data-toggle="tooltip"--}}
								        {{--data-placement="bottom"--}}
								        {{--data-user=" {{json_encode($user)}} "--}}
								        {{--data-target="#editUser">--}}
									{{--<i class="fas fa-edit svg-small btn-theme-clair-fort"></i></button>--}}


								{{--DEACTIVATE USER--}}
								{{--<button class="btn-common " id="terminateUserButton"--}}
								        {{--title="{{trans('app.deactivate')}}"--}}
								        {{--data-user_id=" {{json_encode($user->user_id)}} "--}}
								        {{--data-user_first_name=" {{json_encode($user->user_first_name)}} "--}}
								        {{--data-user_last_name=" {{json_encode($user->user_last_name)}} "--}}
								        {{--data-toggle="modal"--}}
								        {{--data-toggle="tooltip"--}}
								        {{--data-placement="bottom"--}}
								        {{--@if($user->user_status != config('constants.status_active')) hidden @endif--}}
								        {{--data-target="#terminateUser" --}}
								        {{--disabled>--}}
									{{--<i class="fas fa-check svg-small btn-theme-clair-fort"></i></button>--}}

								{{--ACTIVATE USER--}}
								{{--<button class="btn-common " id="activateUserButton"--}}
								        {{--title="{{trans('app.activate')}}"--}}
								        {{--data-user_id=" {{json_encode($user->user_id)}} "--}}
								        {{--data-user_first_name=" {{json_encode($user->user_first_name)}} "--}}
								        {{--data-user_last_name=" {{json_encode($user->user_last_name)}} "--}}
								        {{--data-toggle="modal"--}}
								        {{--data-toggle="tooltip"--}}
								        {{--data-placement="bottom"--}}
								        {{--@if($user->user_status != config('constants.status_terminated')) hidden @endif--}}
								        {{--data-target="#activateUser" --}}
								        {{--disabled>--}}
									{{--<i class="fas fa-undo svg-small btn-theme-clair-fort"></i></button>--}}

								{{--DELETE USER--}}
								{{--<button class="btn-common " id="deleteUserButton"--}}
								        {{--title="{{trans('app.delete')}}"--}}
								        {{--data-toggle="modal"--}}
								        {{--data-toggle="tooltip"--}}
								        {{--data-placement="bottom"--}}
								        {{--data-user_id=" {{json_encode($user->user_id)}} "--}}
								        {{--data-user_first_name=" {{json_encode($user->user_first_name)}} "--}}
								        {{--data-user_last_name=" {{json_encode($user->user_last_name)}} "--}}
								        {{--data-target="#deleteUser" disabled>--}}
									{{--<i class="fas fa-trash svg-small btn-theme-clair-fort"></i></button>--}}
							{{--</div>--}}
						{{--</td>--}}
						{{--<td>{{$user->user_last_name}}</td>--}}
						{{--<td>{{$user->user_first_name}}</td>--}}
						{{--<td>{{$user->user_login}}</td>--}}
						{{--<td>{{$user->user_role_name}}</td>--}}
						{{--<td>{{$user->user_email}}</td>--}}
						{{--@if(auth()->user()->roles->name == (config('constants.role_admin')))--}}
							{{--<td class="text-center">{{$user->user_status}}</td>--}}
						{{--@endif--}}
						{{--<td class="text-center">{{$user->user_trigramme}}</td>--}}
						{{--<td class="text-center">{{$user->user_department_name}}</td>--}}
						{{--<td class="text-center">{{$user->user_service_name}}</td>--}}
						{{--@if(session()->get('budget_option') == 1)--}}
							{{--<td class="text-right"--}}
							    {{--data-value="{{$user->user_daily_cost}}">{{ number_format( $user->user_daily_cost, 0, ',', ' ') }} €--}}
							{{--</td>--}}
						{{--@endif--}}
					{{--</tr>--}}
				{{--@endforeach--}}
				</tbody>
			</table>
		{{--</div>--}}
		<div class="text-center total-separator"></div>
@include('users.user_modals')

@endsection
