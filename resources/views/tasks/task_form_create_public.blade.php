<div class="flex-row flex-wrap-yes justify-evenly items-flex-start pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
	<div class="flex-col flex-wrap-no justify-center pad-h-only-small">

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_name", 'Nom', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::text("task_name", null,["class"=>"form-control m-field-input", 'autofocus', 'placeholder'=>'Nom', 'required', 'tabindex'=>'1']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_type_id", trans('task.Type'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("task_type_id", $task_types, null, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'2']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("activity_id", trans('activity.Name'),["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("activity_id", $activities_list, null, ["id" => "task_activities_list", "class"=>"form-control m-field-input", 'placeholder'=>trans(' '), 'required', 'tabindex'=>'3']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("phase_id", "Phases", ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("phase_id", isset($phases_list) ? $phases_list : [], null, ["id" => "task_phases_list", "class"=>"form-control m-field-input",'placeholder'=>trans(' '), 'required', 'tabindex'=>'4']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>
	</div>

	<div class="flex-col flex-wrap-no justify-center pad-h-only-small">

		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_days_p", 'Prévu', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::number("task_days_p", null, ["class"=>"form-control m-field-input style-prevu", 'step'=>'any','placeholder'=>'Prévu', 'tabindex'=>'5']) }}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("numberSelect", 'Occurence(s)', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::selectRange("numberSelect", 1, 12, null,["class"=>"form-control m-field-input", 'tabindex'=>'6']) }}
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_start_p", 'Début', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::date("task_start_p", null, ["class"=>"form-control m-field-input", "format"=>"d-m-Y", 'placeholder'=>trans('activity.StartP'), 'required', 'tabindex'=>'7']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_end_p", 'Échéance', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::date("task_end_p", null,["class"=>"form-control m-field-input", 'placeholder'=>'Échéance', 'tabindex'=>'8']) }}
		</div>
	</div>

</div>


<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium ln-t-solid-small width-100">
	<label for="task_description" class="form-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control m-area-input" name="task_description"
	       id="task_description" value="" tabindex="40">
</div>

