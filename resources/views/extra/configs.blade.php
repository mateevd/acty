@extends('layouts.app')
@section('content')

	@if(Session::has('flashy_notification.message'))
		<script id="flashy-template" type="text/template">
			<div class="flashy flashy--{{ Session::get('flashy_notification.type') }}">
				<i class="flashy__body"></i>
			</div>
		</script>
	@endif


	<div class="flex-col flex-wrap-no justify-center">
		<div class="config-title">{{trans('passwords.lab_change_password')}}</div>

		@if (session('error'))
			<div class="session-message session-error">{{ session('error') }}</div>@endif
		<form id="password_change" class="flex-col flex-wrap-no items-center hide-submit config-form" method="POST"
		      action="{{ route('Configuration') }}" aria-label="{{trans('passwords.lab_change_password')}}">
			@csrf

			<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-15 pad-v-small">
					<input autocomplete="username" type="text" hidden>
					<label for="current-password"
					       class="dashboard-label text-center">{{trans('passwords.lab_current_password')}}</label>
					<div class="flex-row flex-wrap-no marg-none">
						<i class="fas fa-key svg-inside"></i>
						<input id="current-password" autocomplete="current-password" type="password" name="current-password"
						       class="form-control modal-custom-fields-input" required autofocus tabindex="1">
					</div>
				</div>
				<div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-15 pad-v-small {{ $errors->has('new-password') ? ' has-error' : '' }}">
					<label for="new-password"
					       class="dashboard-label text-center">{{trans('passwords.lab_new_password')}}</label>
					<div class="flex-row flex-wrap-no marg-none">
						<i class="fas fa-key svg-inside"></i>
						<input id="new-password" autocomplete="new-password" type="password" name="new-password"
						       class="form-control modal-custom-fields-input" required tabindex="2">
					</div>
				</div>
				<div class="flex-col flex-wrap-no justify-flex-end text-center  width-rem-15 pad-v-small {{ $errors->has('new-password-confirm') ? ' has-error' : '' }}">
					<label for="new-password-confirm"
					       class="dashboard-label text-center">{{trans('passwords.lab_new_password_confirm')}}</label>
					<div class="flex-row flex-wrap-no marg-none">
						<i class="fas fa-key svg-inside"></i>
						<input id="new-password-confirm" autocomplete="new-password" type="password"
						       name="new-password-confirm" class="form-control modal-custom-fields-input" required
						       tabindex="3">
					</div>
				</div>
			</div>

			<div class="config-bottom">
				<button id="btn-submit-form-password" class="modal-custom-btn-horizontal" tabindex="101"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        title="{{trans('app.ok')}}">
					<i class="far fa-check-circle"></i>
				</button>

			</div>
		</form>
	</div>
	<div class="config-separator ln-b-dashed-small-fonce"></div>

	<div class="flex-col flex-wrap-no justify-center">
		<div class="config-title">{{trans('users.lab_display')}}</div>

		<form id="display_change" action="{{route('users.settings')}}" method="post"
		      class="flex-col flex-wrap-no justify-center items-center hide-submit config-form">
			{{method_field('post')}}
			{{csrf_field()}}

			<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">


				<div class="flex-col flex-wrap-no justify-center pad-v-small width-rem-20">
					<div class="dashboard-label text-center">{{trans('users.lab_theme_desc')}}</div>
					<select name="theme" id="theme" class=" form-control modal-custom-fields-input justify-center">
						<option value="{{config('constants.theme_def')}}" @if(session()->get('theme') == config('constants.theme_def')) selected @endif>{{trans('app.theme_main')}}</option>
						<option value="{{config('constants.theme_01')}}" @if(session()->get('theme') == config('constants.theme_01')) selected @endif>{{trans('app.theme_01')}}</option>
						<option value="{{config('constants.theme_02')}}" @if(session()->get('theme') == config('constants.theme_02')) selected @endif>{{trans('app.theme_02')}}</option>
						<option value="{{config('constants.theme_03')}}" @if(session()->get('theme') == config('constants.theme_03')) selected @endif>{{trans('app.theme_03')}}</option>
					</select>
				</div>

				<div class="flex-col flex-wrap-no justify-center pad-v-small width-rem-20">
					<div class="dashboard-label text-center">{{trans('users.lab_zoom_desc')}}</div>
					<select name="zoom" id="zoom" class="form-control modal-custom-fields-input">
						<option value="{{config('constants.zoom_def')}}" @if(session()->get('zoom') == config('constants.zoom_def')) selected @endif>{{trans('app.zoom_def')}}</option>
						<option value="{{config('constants.zoom_75')}}" @if(session()->get('zoom') == config('constants.zoom_75')) selected @endif>{{trans('app.zoom_75')}}</option>
						<option value="{{config('constants.zoom_100')}}" @if(session()->get('zoom') == config('constants.zoom_100')) selected @endif>{{trans('app.zoom_100')}}</option>
						<option value="{{config('constants.zoom_125')}}" @if(session()->get('zoom') == config('constants.zoom_125')) selected @endif>{{trans('app.zoom_125')}}</option>
					</select>
				</div>
			</div>

			<div class="config-bottom">
				<button id="btn-submit-form-display" class="modal-custom-btn-horizontal" tabindex="101"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        title="{{trans('app.ok')}}">
					<i class="far fa-check-circle"></i>
				</button>
			</div>
		</form>
	</div>

	<div class="text-center table-separator"></div>

@endsection
