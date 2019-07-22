<div class="flex-row flex-wrap-yes justify-evenly items-flex-start pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small flex-grow-1">

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("task_name", 'Nom', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::text("task_name", null,["class"=>"form-control modal-custom-fields-input", 'autofocus', 'placeholder'=>'Nom', 'required', 'tabindex'=>'1']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("task_type_id", trans('task.Type'),["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::select("task_type_id", $task_types, null, ["class"=>"form-control modal-custom-fields-input", 'required', 'tabindex'=>'2']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("activity_id", trans('activity.Name'),["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::select("activity_id", $activities_list, null, ["id" => "task_activities_list", "class"=>"form-control modal-custom-fields-input", 'placeholder'=>trans(' '), 'required', 'tabindex'=>'3']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("phase_id", "Phases", ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::select("phase_id", isset($phases_list) ? $phases_list : [], null, ["id" => "task_phases_list", "class"=>"form-control modal-custom-fields-input",'placeholder'=>trans(' '), 'required', 'tabindex'=>'4']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>
</div>

	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small width-rem-15">

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("task_days_p", 'Prévu', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::number("task_days_p", null, ["class"=>"form-control modal-custom-fields-input style-prevu", 'step'=>'any','placeholder'=>'Prévu', 'tabindex'=>'5']) }}
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("numberSelect", 'Occurence(s)', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::selectRange("numberSelect", 1, 12, null,["class"=>"form-control modal-custom-fields-input", 'tabindex'=>'6']) }}
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("task_start_p", 'Début', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::date("task_start_p", null, ["class"=>"modal-custom-fields-input form-control", "format"=>"d-m-Y", 'placeholder'=>trans('activity.StartP'), 'required', 'tabindex'=>'7']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center pad-v-small align-stretch width-100">
				{{ Form::label("task_end_p", 'Échéance', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
				{{ Form::date("task_end_p", null,["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Échéance', 'tabindex'=>'8']) }}
			</div>
		</div>
	</div>
</div>


<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
	<label for="task_description" class="dashboard-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control modal-custom-area-input" name="task_description"
			id="task_description" value="" tabindex="40">
</div>

