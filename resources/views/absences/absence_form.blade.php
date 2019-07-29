{{--MODAL CREATE --}}
<div class="modal" id="addAbsence" tabindex="-1" role="dialog" aria-labelledby="Ajouter une absence">
	<div class="modal-dialog modal-large" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">Ajouter</div>
			<div class="modal-custom-libelle">Absence</div>

			<form id="absence_create" action="{{route('absences.create')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('POST')}}
				{{csrf_field()}}

				<input type="hidden" name="id" id="id" value="">
				<input type="hidden" name="week" id="week" value="">

				<div class="flex-row flex-wrap-yes justify-center items-center pad-v-small pad-h-medium bg-important-back width-100 ln-b-dashed-small">
					{{ Form::label("absence_date", trans('absences.lab_absence_date'),["class"=>"style-important control-label pad-h-medium"]) }}
					<span class="pad-h-medium">
						{{ Form::date("absence_date", \Carbon\Carbon::parse($current_date)->startOfMonth(),["class"=>"form-control m-field-input style-important", 'placeholder'=>trans('absences.lab_total_days'), 'required', 'tabindex'=>'1']) }}
						<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_date')}}</div>
						<div class="invalid-feedback show-warning-small pad-v-top-medium"></div>
					</span>
					<span class="pad-h-medium">
						<i class="fas fa-exclamation-triangle btn-common  svg-huge style-glowing-text pos-rel-2"></i>
					</span>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small pad-v-bottom-medium ln-b-solid-small width-100">
					<div class="flex-col flex-wrap-no justify-flex-end text-center pad-h-only-small">
						{{ Form::label("absence_type_id", trans('absences.lab_type'),["class"=>"form-label"]) }}
						{{ Form::select("absence_type_id", $absence_types, null,["class"=>"form-control m-field-input", 'tabindex'=>'2']) }}
					</div>
					<div class="flex-col flex-wrap-no justify-flex-end text-center pad-h-only-small">
						{{ Form::label("occurenceSelect", trans('absences.lab_occurrence'), ["class"=>"form-label control-label"]) }}
						{{ Form::selectRange("occurenceSelect", 1, 12, null,["class"=>"form-control m-field-input", 'tabindex'=>'3']) }}
					</div>
				</div>

				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center ln-t-solid-small width-100">
					<label for="hours_history" class="form-label">Ajouter / Ôter des jours</label>
				</div>

				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center">

					<div class="flex-col flex-wrap-no justify-center items-center">
						<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small" id="hoursBtns">
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="0.5" tabindex="10"
								        class="modal-custom-btn-absence modal-custom-btn-absence-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ajouter 0,5 jour">+0,5 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="1" tabindex="11"
								        class="modal-custom-btn-absence modal-custom-btn-absence-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ajouter 1 jour">+1 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="2" tabindex="12"
								        class="modal-custom-btn-absence modal-custom-btn-absence-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ajouter 2 jours">+2 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="5" tabindex="13"
								        class="modal-custom-btn-absence modal-custom-btn-absence-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ajouter 5 jours">+5 j
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center">

					<div class="flex-col flex-wrap-no justify-center items-center">
						<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small" id="hoursBtns">
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-0.5" tabindex="20"
								        class="modal-custom-btn-absence modal-custom-btn-absence-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ôter 0,5 jour">-0,5 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-1" tabindex="21"
								        class="modal-custom-btn-absence modal-custom-btn-absence-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ôter 1 jour">-1 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-2" tabindex="22"
								        class="modal-custom-btn-absence modal-custom-btn-absence-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ôter 2 jours">-2 j
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-5" tabindex="23"
								        class="modal-custom-btn-absence modal-custom-btn-absence-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="Ôter 5 jours">-5 j
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="flex-row pad-h-medium pad-v-small flex-wrap-no justify-center align-center">

					<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small flex-wrap-no justify-center align-center" id="hoursBtns">
						<label for="absence_days" class="control-label" hidden></label>
						<input type="number" id="absence_days" name="absence_days"
						       class="form-control m-field-input input-hours-absences" count="0"
						       value="0"
						       step="any" ex_value="0" placeholder="{{trans('absences.lab_total_days')}}"
						       required='required' disabled>
					</div>
					<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small flex-wrap-no justify-center align-center" id="delhoursBtn">
						<button type="button" value="0" class="modal-custom-btn-absence-reset" tabindex="30"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        title="Réinitialiser le compteur"><i class="fas fa-sync-alt"></i>
						</button>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
					<label for="absence_description" class="form-label">{{ucfirst(trans('app.description'))}}</label>
					<input type="textarea" class="form-control m-area-input" name="absence_description"
					       id="absence_description" value="" tabindex="40">
				</div>

				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
					        data-dismiss="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button class="modal-custom-btn-horizontal" tabindex="101" id="btn-submit-form"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.ok')}}">
						<i class="far fa-check-circle"></i>
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
