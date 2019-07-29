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
			{{ Form::label("task_milestone", 'Milestone', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::select("task_milestone", $milestones, null, ["class"=>"form-control m-field-input", 'required', 'tabindex'=>'7']) }}
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
			{{ Form::date("task_start_p", null, ["class"=>"form-control m-field-input", "format"=>"d-m-Y", 'placeholder'=>trans('activity.StartP'), 'required', 'tabindex'=>'3']) }}
			<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
		</div>
		<div class="flex-col flex-wrap-no justify-center pad-v-top-small pad-v-bottom-medium">
			{{ Form::label("task_end_p", 'Échéance', ["class"=>"flex-row flex-wrap-yes justify-evenly form-label control-label"]) }}
			{{ Form::date("task_end_p", null,["class"=>"form-control m-field-input", 'placeholder'=>'Échéance', 'tabindex'=>'4']) }}
		</div>
	</div>

	<div class="flex-col flex-wrap-no justify-center pad-h-small pad-v-top-small">
		<div class="flex-row flex-wrap-no justify-center form-label">Affectation</div>
		<div class="flex-row flex-wrap-yes justify-between">
			<div class="flex-row flex-wrap-yes justify-center pad-h-right-small width-50">
				<ol data-draggable="target" class="flex-col flex-wrap-no justify-flex-start form-control m-field-input text-left marg-none height-rem-20 data-draggable-src" tabindex="10">
					@foreach($all_direction_users as $key=>$value)
						<li data-value="{{$key}}" data-draggable="item" tabindex="11" @if($key == auth()->user()->id) class="style-realise" @endif>{{$value}}</li>
					@endforeach
				</ol>
			</div>

			<div class="flex-row flex-wrap-yes justify-center pad-h-left-small width-50">
				<ol data-draggable="target" class="flex-col flex-wrap-no justify-flex-start form-control m-field-input text-left marg-none height-rem-20 data-draggable-dest" tabindex="20" id="user_id">
				</ol>
			</div>
		</div>
		<div class="flex-row flex-wrap-yes justify-center style-ids pad-h-none">(Cliquez/déposez les éléments d'une fenêtre à l'autre - La multi sélection avec la touche CTRL enfoncée est possible)</div>
	</div>

</div>

<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium ln-t-solid-small width-100">
	<label for="task_description" class="form-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control m-area-input" name="task_description"
	       id="task_description" value="" tabindex="40">
</div>
