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

		<div class="flex-row flex-wrap-yes justify-center">
			<div class="flex-col flex-wrap-no justify-center pad-h-small pad-v-small align-stretch">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("task_start_p", 'Début', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
					{{ Form::date("task_start_p", null, ["class"=>"modal-custom-fields-input form-control", "format"=>"d-m-Y", 'placeholder'=>trans('activity.StartP'), 'required', 'tabindex'=>'9']) }}
					<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
				</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center pad-h-small pad-v-small align-stretch">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("task_end_p", 'Échéance', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
					{{ Form::date("task_end_p", null,["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Échéance']) }}
				</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-center">
			<div class="flex-col flex-wrap-no justify-center pad-h-small pad-v-small align-stretch">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("task_start_r", 'Début réalisé', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
					{{ Form::date("task_start_r", null, ["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Début réalisé', 'disabled']) }}
				</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center pad-h-small pad-v-small align-stretch">
				<div class="flex-col flex-wrap-no justify-flex-end text-center">
					{{ Form::label("task_end_r", 'Fin réalisé', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
					{{ Form::date("task_end_r", null, ["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Fin réalisé', 'disabled']) }}
				</div>
			</div>
		</div>

	</div>

	<div class="flex-col flex-wrap-no justify-flex-start align-stretch pad-h-only-small width-rem-15">
		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small">
			{{ Form::label("task_days_p", 'Prévu', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
			{{ Form::number("task_days_p", null, ["class"=>"form-control modal-custom-fields-input style-prevu", 'step'=>'any','placeholder'=>'Prévu']) }}
		</div>
		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small">
			{{ Form::label("task_milestone", 'Milestone', ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
			{{ Form::select("task_milestone", $milestones, null, ["class"=>"form-control modal-custom-fields-input", 'required', 'tabindex'=>'7']) }}
		</div>
	</div>
</div>

<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
		{{ Form::label("task_user_id", "Affectation", ["class"=>"flex-row flex-wrap-yes justify-evenly global-modal-label control-label"]) }}
		{{ Form::select("task_user_id", $all_direction_users, null, ["class"=>"form-control modal-custom-fields-input text-left", 'placeholder'=> ' ' , 'tabindex'=>'7']) }}
</div>

<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
	<label for="task_description" class="dashboard-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control modal-custom-area-input" name="task_description"
			id="task_description" value="" tabindex="40">
</div>
