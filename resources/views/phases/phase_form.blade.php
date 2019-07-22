<div class="flex-row flex-wrap-yes justify-evenly items-flex-start pad-all-medium">
	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small">

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center align-stretch width-100 pad-all-small">
				{{ Form::label("phase_name", 'Nom', ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label pad-v-none"]) }}
				{{ Form::text("phase_name", null,["class"=>"form-control modal-custom-fields-input", "autofocus", 'placeholder'=>'Nom', 'required', 'tabindex'=>'1']) }}
				<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
			</div>
		</div>

		<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-none">
			<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center pad-all-small">
					{{ Form::label("phase_start_p", 'Début', ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium global-modal-label"]) }}
					{{ Form::date("phase_start_p", null, ["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Début', 'required', 'tabindex'=>'2']) }}
					<div class="invalid-feedback show-warning-small">{{ucfirst(trans('app.mandatory'))}}</div>
				</div>
			</div>
			<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small">
				<div class="flex-col flex-wrap-no justify-flex-end text-center pad-all-small">
					{{ Form::label("phase_end_p", 'Échéance', ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium global-modal-label"]) }}
					{{ Form::date("phase_end_p", null, ["class"=>"form-control modal-custom-fields-input", 'placeholder'=>'Échéance', 'tabindex'=>'3']) }}
				</div>
			</div>
		</div>

	</div>

	<div class="flex-col flex-wrap-no justify-center align-stretch pad-h-only-small justify-between">
		<div class="flex-col flex-wrap-no justify-center align-stretch width-100 pad-all-small">
			{{Form::label("phase_private", 'Visibilité', ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label pad-v-none"])}}
			{{Form::select("phase_private", $privacies, null , ["class"=>"form-control modal-custom-fields-input", 'tabindex'=>'4'])}}
		</div>
	</div>
</div>

<div class="flex-row flex-wrap-yes justify-evenly pad-all-medium width-100">
	<label for="phase_description" class="dashboard-label">{{ucfirst(trans('app.description'))}}</label>
	<input type="textarea" class="form-control modal-custom-area-input" name="phase_description"
			id="phase_description" value="" tabindex="40">
</div>
