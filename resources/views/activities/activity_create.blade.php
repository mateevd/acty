<div class="flex-row flex-wrap-yes justify-evenly items-flex-start pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
	<div class="flex-col flex-wrap-no justify-center pad-h-only-small">

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_name", trans('activity.Name'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::text("activity_name", null,["class"=>"form-control m-field-input", 'autofocus', 'placeholder'=>'Nom', 'required', 'tabindex'=>'1']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_code", trans('activity.Code'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::text("activity_code", null,["class"=>"form-control m-field-input", 'placeholder'=>trans('activity.Code'), 'required', 'tabindex'=>'2']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_manager", 'Responsable', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_manager", $managers, null, ["class"=>"form-control m-field-input",'placeholder'=>trans(' '), 'tabindex'=>'3']) }}
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_deputy", 'Responsable adjoint', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_deputy", $deputies, null, ["class"=>"form-control m-field-input",'placeholder'=>trans(' '), 'tabindex'=>'4']) }}
		</div>

		<div class="flex-row flex-wrap-yes justify-between pad-v-top-small pad-v-bottom-medium">
			<div class="flex-col flex-wrap-no justify-center pad-h-right-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("activity_start_p", trans('activity.StartP'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
					{{ Form::date("activity_start_p", null, ["class"=>"form-control m-field-input", "format"=>"d-m-Y", 'placeholder'=>trans('activity.StartP'), 'required', 'tabindex'=>'5']) }}
					<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
				</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center pad-h-left-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("activity_end_p", trans('activity.EndP'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
					{{ Form::date("activity_end_p",null,["class"=>"form-control m-field-input", 'placeholder'=>trans('activity.EndP'), 'tabindex'=>'6']) }}
				</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-between pad-v-top-small pad-v-bottom-medium">
			<div class="flex-col flex-wrap-no justify-center pad-h-right-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("activity_date_requested", trans('activity.DateR'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
					{{ Form::date("activity_date_requested",null,["class"=>"form-control m-field-input", 'placeholder'=>trans('activity.DateR'), 'tabindex'=>'7']) }}
				</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center pad-h-left-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("activity_date_limit", trans('activity.DateLimit'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
					{{ Form::date("activity_date_limit", null, ["class"=>"form-control m-field-input ", 'placeholder'=>trans('activity.DateLimit'), 'tabindex'=>'8']) }}
				</div>
			</div>
		</div>

	</div>

	<div class="flex-col flex-wrap-no justify-center pad-h-only-small">
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_priority_id", trans('activity.Priority'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_priority_id", $priorities, null, ["class"=>"form-control m-field-input", 'placeholder'=>trans(' '), 'tabindex'=>'9']) }}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_type_id", trans('activity.Type'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_type_id", $activity_types, null, ["class"=>"form-control m-field-input", 'placeholder'=>trans(' '), 'tabindex'=>'10']) }}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_enveloppe", trans('activity.EnveloppeSimple'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::number("activity_enveloppe", null, ["class"=>"form-control m-field-input style-enveloppe-important", 'step'=>'1', 'min'=>'0', 'placeholder'=>trans('activity.Enveloppe'), 'tabindex'=>'11']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.positive_int_0'))}}</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_business_id", trans('activity.BusinessDep'), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_business_id", $businesses, null, ["class"=>"form-control m-field-input",'placeholder'=>trans(' '), 'tabindex'=>'12'])}}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{Form::label("activity_private", 'Visibilité',["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"])}}
			{{Form::select("activity_private", $privacies, null ,["class"=>"form-control m-field-input", 'tabindex'=>'13'])}}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{!! Form::label("activity_state_id", trans('activity.State'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) !!}
			{!! Form::select("activity_state_id", $states, null,["class"=>"form-control m-field-input", 'tabindex'=>'14']) !!}
		</div>
	</div>



	@if(session()->get('budget_option') == 1)
		<div class="flex-row flex-wrap-yes justify-evenly width-100">
			<div class="flex-col flex-wrap-no justify-center pad-v-small">
				{{ Form::label("activity_charges_investment", ucfirst(trans('activity.capex')), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
				{{ Form::number("activity_charges_investment", null, ["class"=>"form-control m-field-input", 'step'=>'1', 'min'=>'0', 'placeholder'=>strtoupper(trans('app.capex')), 'tabindex'=>'20']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.positive_int_0'))}}</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center pad-v-small">
				{{ Form::label("activity_charges_operation", ucfirst(trans('activity.opex')), ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
				{{ Form::number("activity_charges_operation", null, ["class"=>"form-control m-field-input", 'step'=>'1', 'min'=>'0', 'placeholder'=>strtoupper(trans('app.opex')), 'tabindex'=>'21']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.positive_int_0'))}}</div>
			</div>
		</div>
	@endif
</div>

<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium ln-t-solid-small width-100">
	<label for="activity_description" class="form-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control m-area-input" name="activity_description"
	       id="activity_description" value="" tabindex="40">
</div>








