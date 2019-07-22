{{--MODAL ADD HOURS--}}
<div class="modal" id="addHours" tabindex="-1" role="dialog" aria-labelledby="Ajouter une absence">
	<div class="modal-dialog modal-large" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{trans('work_days.act_create_hours')}}</div>
			<div class="modal-custom-libelle">
				<span id="task_name"></span>&nbsp;<span id="task_date"></span>
			</div>

			<form  id="wday_create" action="{{route('wday.create')}}" method="post" class="needs-validation hide-submit modal-custom-form"
			      novalidate>
				{{method_field('POST')}}
				{{csrf_field()}}

				<input type="hidden" name="task_id" id="task_id" value="">
				<input type="hidden" name="phase_id" id="phase_id" value="">
				<input type="hidden" name="activity_id" id="activity_id" value="">

				<div class="flex-row flex-wrap-yes justify-center items-center pad-v-small pad-h-medium bg-important-back width-100 ln-b-dashed-small">
					{{ Form::label("work_day_date", trans('task.lab_real_day'),["class"=>"style-important control-label pad-h-medium"]) }}
					<span class="pad-h-medium">
						{{ Form::date("work_day_date", \Carbon\Carbon::parse($current_date)->startOfMonth(),["class"=>"form-control modal-custom-fields-input style-important", 'placeholder'=>trans('task.lab_real_day'), 'required', 'tabindex'=>'1']) }}
						<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_date')}}</div>
						<div class="invalid-feedback show-warning-small pad-v-top-medium"></div>
					</span>
					<span class="pad-h-medium">
						<i class="fas fa-exclamation-triangle btn-common  svg-huge style-glowing-text pos-rel-2"></i>
					</span>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small width-100 bg-clair-fort">
					<div class="flex-col flex-wrap-no items-center flex-shrink-1 flex-basis-50 pad-all-small">
						<div class="dashboard-label text-center bold">Activité</div>
						<div class=" text-center wrap-no phases-activity-data-no-bold" id="task_activity_name"></div>
					</div>

					<div class="flex-col flex-wrap-no items-center flex-shrink-1 flex-basis-50 pad-all-small">
						<div class="dashboard-label text-center bold">Responsable de l'activité</div>
						<div class=" text-center wrap-no phases-activity-data-no-bold" id="task_activity_cpi_name"></div>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium width-100 bg-clair-fort">
					<div class="flex-col flex-wrap-no items-center flex-shrink-1 flex-basis-50 pad-all-small">
						<div class="dashboard-label text-center bold">Phase rattachée</div>
						<div class=" text-center wrap-no phases-activity-data-no-bold" id="task_phase_name"></div>
					</div>
					<div class="flex-col flex-wrap-no items-center flex-shrink-1 flex-basis-50 pad-all-small">
						<div class="dashboard-label text-center bold">Type de la tâche</div>
						<div class=" text-center wrap-no phases-activity-data-no-bold" id="task_type"></div>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium width-100 bg-clair-fort" id="is_description">
					<div class="flex-col flex-wrap-no items-center flex-shrink-1 flex-basis-33 pad-all-small">
							<div class="dashboard-label text-center bold">{{ucfirst(trans('app.description'))}} de la tâche</div>
							<div class=" text-center wrap-yes phases-activity-data-no-bold style-description" id="task_description"></div>
					</div>
				</div>


				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center ln-t-solid-small width-100">
					<label class="dashboard-label text-center bold">Ajouter / Ôter du temps</label>
				</div>
				
				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center  width-100">

					<div class="flex-col flex-wrap-no justify-center items-center">
						<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small" id="hoursBtns">
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="0.0625" tabindex="10"
								        class="modal-custom-btn-hours modal-custom-btn-hours-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="30 minutes (soit 0,0625 jour)">+30 m
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="0.125" tabindex="11"
								        class="modal-custom-btn-hours modal-custom-btn-hours-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="1 heure (soit 0,125 jour)">+1 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="0.25" tabindex="12"
								        class="modal-custom-btn-hours modal-custom-btn-hours-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="2 heures (soit 0,25 jour)">+2 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="0.5" tabindex="13"
								        class="modal-custom-btn-hours modal-custom-btn-hours-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="4 heures (soit 0,5 jour)">+4 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="1" tabindex="14"
								        class="modal-custom-btn-hours modal-custom-btn-hours-add"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="8 heures (soit 1 jour)">+8 h
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes pad-h-medium pad-v-small justify-center items-center  width-100">
					
					<div class="flex-col flex-wrap-no justify-center items-center">
						<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small" id="hoursBtns">
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-0.0625" tabindex="20"
								        class="modal-custom-btn-hours modal-custom-btn-hours-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="-30 minutes (soit -0,0625 jour)">-30 m
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-0.125" tabindex="21"
								        class="modal-custom-btn-hours modal-custom-btn-hours-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="-1 heure (soit -0,125 jour)">-1 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-0.25" tabindex="22"
								        class="modal-custom-btn-hours modal-custom-btn-hours-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="-2 heures (soit -0,25 jour)">-2 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-0.5" tabindex="23"
								        class="modal-custom-btn-hours modal-custom-btn-hours-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="-4 heures (soit -0,5 jour)">-4 h
								</button>
							</div>
							<div class="flex-row flex-wrap-yes justify-evenly pad-v-small pad-h-only-small">
								<button type="button" value="-1" tabindex="24"
								        class="modal-custom-btn-hours modal-custom-btn-hours-doff"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="-8 heures (soit -1 jour)">-8 h
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes justify-center items-center pad-h-medium pad-v-small width-100">
					<div class="flex-row pad-all-small items-center" id="hoursBtns">
						<input type="number" id="work_day_days" name="work_day_days"
						       class="form-control modal-custom-fields-input input-hours-absences add-hours-readonly" count="0" value="0"
						       step="any" ex_value="0" placeholder="{{trans('task.labdays_real')}}" required='required'
						       disabled>
						<label for="work_day_days" class="add-hours-readonly-label pad-h-right-medium"><b>Jours</b></label>
					</div>
					<div class="flex-row pad-all-small items-center" id="hoursBtns">
						<input type="number" id="hours" name="hours"
						       class="form-control modal-custom-fields-input input-hours-absences add-hours-readonly opacity-5" count="0" value="0"
						       step="any" ex_value="0" placeholder="{{trans('task.labdays_real')}}" required='required'
						       disabled>
						<label for="hours" class="add-hours-readonly-label pad-h-right-medium">Heures</label>
					</div>
					<div class="flex-row pad-all-small items-center" id="delhoursBtn">
						<button type="button" value="0" class="modal-custom-btn-hours-reset" tabindex="30"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        title="Réinitialiser le compteur"><i class="fas fa-sync-alt"></i></button>
					</div>
				</div>

				<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-top-small pad-v-bottom-medium width-100">
					<label for="work_day_description" class="dashboard-label bold">{{ucfirst(trans('app.description'))}}</label>
					<input type="textarea" class="form-control modal-custom-area-input" name="work_day_description"
							id="work_day_description" value="" tabindex="40">
				</div>


				<div class="flex-row flex-wrap-yes justify-evenly items-center marg-v-bottom-medium pad-v-top-medium width-100 bg-clair-fort" id="is_history">
					<label for="hours_history" class="dashboard-label text-center pad-v-bottom-small">{{trans('task.hours_history')}}</label>

					<div class="table-responsive height-max-rem-10">
						<table class="table sortable add-hours-table">
							<thead>
							<tr>
								<th class="text-right tiny-cell">{{trans('app.lab_realise')}}</th>
								<th data-defaultsort="asc" class="text-center tiny-cell">{{ucfirst(trans('app.lab_date'))}}</th>
								<th class="">{{ucfirst(trans('app.description'))}}</th>
								<th class="text-center tiny-cell">TRI</th>
							</tr>
							</thead>
							<tbody id="wdays_table">
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal-custom-detail style-ids" id="task_id"></div>
				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
					        data-dismiss="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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