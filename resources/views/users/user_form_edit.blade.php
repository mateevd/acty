<div class="flex-row flex-wrap-yes justify-evenly items-flex-start pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">

	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small">
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_first_name", trans('users.lab_first_name'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::text("user_first_name", null,["class"=>"form-control m-field-input", 'required', 'tabindex'=>'1']) }}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_login", trans('users.lab_login'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::text("user_login", null, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'3']) }}
			<div class="invalid-feedback show-warning-small">Obigatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{!! Form::label("user_department_id2", trans('users.Department'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) !!}
			</div>
			@if(auth()->user()->role_id == config('constants.role_admin_id'))
				{!! Form::select("user_department_id2", $departments, null,["class"=>"form-control m-field-input", 'required', 'tabindex'=>'5']) !!}
			@else
				{!! Form::select("user_department_id2", $departments, null,["class"=>"form-control m-field-input", 'required', 'tabindex'=>'5', 'disabled']) !!}
			@endif
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_trigramme", trans('users.lab_trigram'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::text("user_trigramme", null, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'7']) }}
			<div class="invalid-feedback show-warning-small">Obigatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_email", trans('users.lab_mail'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::email("user_email", null, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'9'])}}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
	</div>

	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small justify-between">
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_last_name", trans('users.lab_last_name'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::text("user_last_name", null,["class"=>"form-control m-field-input", 'required', 'tabindex'=>'2']) }}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{!! Form::label("current-password", trans('users.lab_password'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) !!}
			</div>
			{!! Form::password("current-password", ["class"=>"form-control m-field-input", 'required', 'autocomplete', 'tabindex'=>'4']) !!}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{!! Form::label("user_service_id2", trans('users.Service'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) !!}
			</div>
			{!! Form::select("user_service_id2", $services, null, ["class"=>"form-control m-field-input", 'tabindex'=>'6']) !!}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
			<div class="flex-row flex-wrap-no justify-center">
				<div class="show-warning-small pad-h-right-mini">*</div>
				{{ Form::label("user_role_id", trans('users.lab_role'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			</div>
			{{ Form::select("user_role_id", $roles, 5, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'8']) }}
			<div class="invalid-feedback show-warning-small">Obligatoire</div>
		</div>

		@if(session()->get('budget_option') == 1)
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				<div class="flex-row flex-wrap-no justify-center">
					<label for="user_daily_cost" class="flex-row flex-wrap-yes justify-evenly form-label control-label">{{trans('users.lab_daily_cost')}}</label>
				</div>
				<input type="number" class="form-control m-field-input" name="user_daily_cost"
				       id="user_daily_cost" value="" min="0" step="1" tabindex="40">
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.positive_int_0'))}}</div>
				<div class="invalid-feedback show-warning-small pad-v-top-medium"></div>
			</div>
		@endif

	</div>
</div>
