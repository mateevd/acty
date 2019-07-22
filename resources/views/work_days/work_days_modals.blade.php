{{--MODAL VALIDATE WD FOR USER--}}
@if(isset($cra_user))
<div class="modal" id="validateWD" tabindex="-1" role="dialog" aria-labelledby="Valider des temps">
	<div class="modal-dialog modal-largest" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.validate'))}}</div>
			<div class="modal-custom-libelle" id="user_name"></div>

			<div class="flex-row flex-wrap-no justify-center items-center text-center pad-v-small marg-none modal-custom-libelle-ex has-search-sticky">
				<i class="fas fa-magic svg-inside-details"></i>
				<input class="form-control universal-filter" id="wd_validate_input" type="text"
				       placeholder="Filtre universel" tabindex="1">
				<i class="modal-custom-btn-horizontal" tabindex="2"
				   title="{{trans('app.back')}}"
				   data-dismiss="modal"
				   data-toggle="tooltip"
				   data-placement="bottom">
					<i class="far fa-times-circle"></i>
				</i>
			</div>

			<form id="wday_validate" action="{{route('wday.validate_wd')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				<input type="hidden" name="wd_id" id="wd_id" value="">
				<input type="hidden" name="charge_month" id="charge_month">
				<input type="hidden" name="charge_year" id="charge_year">

				<div class="table-responsive">
					<table class="table sortable">
						<thead>
						<tr>
							<th>

							</th>
							<th data-defaultsort="asc"  class="action-btn-no-header">{{trans('app.lab_activity')}}</th>
							<th class="truncate-details">{{trans('app.lab_phase')}}</th>
							<th class="truncate-details">{{trans('app.lab_task')}}</th>
							<th class="truncate-details">{{trans('app.lab_task_type')}}</th>
							<th class="text-center truncate-details">Mois de la tâche</th>
							<th class="text-center truncate-details">Datede réalisation</th>
							<th class="truncate-details">Desc.</th>
							<th class="text-right truncate-details">Temps</th>
						</tr>
						</thead>
						<tbody id="wd_validate_table">
						</tbody>
					</table>
				</div>

				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
							data-dismiss="modal"
							data-toggle="tooltip"
							data-placement="bottom"
							title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
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
@endif

{{--MODAL DENY WD FOR USER--}}
@if(isset($cra_user))
<div class="modal" id="denyWD" tabindex="-1" role="dialog" aria-labelledby="Refuser des temps">
	<div class="modal-dialog modal-largest" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.deny'))}}</div>
			<div class="modal-custom-libelle" id="user_name"></div>

			<div class="flex-row flex-wrap-no justify-center items-center text-center pad-v-small marg-none modal-custom-libelle-ex has-search-sticky">
				<i class="fas fa-magic svg-inside-details"></i>
				<input class="form-control universal-filter" id="wd_deny_input" type="text"
				       placeholder="Filtre universel" tabindex="1">
				<i class="modal-custom-btn-horizontal" tabindex="2"
				   title="{{trans('app.back')}}"
				   data-dismiss="modal"
				   data-toggle="tooltip"
				   data-placement="bottom">
					<i class="far fa-times-circle"></i>
				</i>
			</div>

			<form id="wday_deny" action="{{route('wday.deny_wd')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				<input type="hidden" name="wd_id" id="wd_id" value="">
				<input type="hidden" name="charge_month" id="charge_month">
				<input type="hidden" name="charge_year" id="charge_year">

				<div class="table-responsive">
					<table class="table sortable">
						<thead>
						<tr>
							<th>

							</th>
							<th data-defaultsort="asc"  class="action-btn-no-header">{{trans('app.lab_activity')}}</th>
							<th class="truncate-details">{{trans('app.lab_phase')}}</th>
							<th class="truncate-details">{{trans('app.lab_task')}}</th>
							<th class="truncate-details">{{trans('app.lab_task_type')}}</th>
							<th class="text-center truncate-details">Mois de la tâche</th>
							<th class="text-center truncate-details">Datede réalisation</th>
							<th class="truncate-details">Desc.</th>
							<th class="text-right truncate-details">Temps</th>
						</tr>
						</thead>
						<tbody id="wd_deny_table">
						</tbody>
					</table>
				</div>


				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
							data-dismiss="modal"
							data-toggle="tooltip"
							data-placement="bottom"
							title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
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
@endif

{{--MODAL VALIDATE ALL WD--}}
@if(isset($cra_user))
	<div class="modal" id="validateAllWD" tabindex="-1" role="dialog" aria-labelledby="Valider les temps de l'entité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.validate'))}}</div>
				<div class="modal-custom-libelle">{{ucfirst(trans('app.validate_all'))}}</div>
				<div class="modal-custom-description style-important">{{trans('work_days.validate_all_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('work_days.validate_all_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('work_days.validate_all_desc_03')}}</div>

				<form action="{{route('wday.validate_all')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id_in" id="user_id_in">
					<input type="hidden" name="current_month" id="current_month">
					<input type="hidden" name="current_year" id="current_year">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.ok')}}">
							<i class="far fa-check-circle"></i></button>
					</div>

				</form>

			</div>
		</div>
	</div>
@endif

{{--MODAL DENY ALL WD--}}
@if(isset($cra_user))
	<div class="modal" id="denyAllWD" tabindex="-1" role="dialog" aria-labelledby="Refuser tous les temps de l'entité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.deny'))}}</div>
				<div class="modal-custom-libelle">{{ucfirst(trans('app.deny_all'))}}</div>
				<div class="modal-custom-description style-important">{{trans('work_days.deny_all_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_all_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_all_desc_03')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_all_desc_04')}}</div>

				<form action="{{route('wday.deny_all')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id_in" id="user_id_in">
					<input type="hidden" name="current_month" id="current_month">
					<input type="hidden" name="current_year" id="current_year">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.ok')}}">
							<i class="far fa-check-circle"></i></button>
					</div>

				</form>

			</div>
		</div>
	</div>
@endif

{{--MODAL VALIDATE WD FOR USER--}}
@if(isset($cra_user))
	<div class="modal" id="validateUserAllWD" tabindex="-1" role="dialog" aria-labelledby="Valider les temps d'un utilisateur">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.validate'))}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>
				<div class="modal-custom-description style-important">{{trans('work_days.validate_user_all_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('work_days.validate_user_all_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('work_days.validate_user_all_desc_03')}}</div>

				<form action="{{route('wday.validate_user_all')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id_in" id="user_id_in">
					<input type="hidden" name="current_month" id="current_month">
					<input type="hidden" name="current_year" id="current_year">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.ok')}}">
							<i class="far fa-check-circle"></i></button>
					</div>

				</form>

			</div>
		</div>
	</div>
@endif

{{--MODAL DENY WD FOR USER--}}
@if(isset($cra_user))
	<div class="modal" id="denyUserAllWD" tabindex="-1" role="dialog" aria-labelledby="Refuser les temps d'un utilisateur">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.deny'))}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>
				<div class="modal-custom-description style-important">{{trans('work_days.deny_user_all_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_user_all_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_user_all_desc_03')}}</div>
				<div class="modal-custom-description">{{trans('work_days.deny_user_all_desc_04')}}</div>

				<form action="{{route('wday.deny_user_all')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id_in" id="user_id_in">
					<input type="hidden" name="current_month" id="current_month">
					<input type="hidden" name="current_year" id="current_year">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.ok')}}">
							<i class="far fa-check-circle"></i></button>
					</div>

				</form>

			</div>
		</div>
	</div>
@endif

{{--MODAL DELETE --}}
<div class="modal" id="deleteTime" tabindex="-1" role="dialog" aria-labelledby="Supprimer une absence">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{trans('app.delete')}}</div>
			<div class="modal-custom-libelle">{{trans('work_days.lab_work_day')}}</div>
			<div class="modal-custom-description">{{trans('work_days.act_delete_confirm')}}</div>
			<div class="modal-custom-description show-warning-fatal">{{trans('app.nowayback')}}</div>

			<form action="{{route('wday.destroy')}}" method="post" class="hide-submit modal-custom-form">
				{{method_field('delete')}}
				{{csrf_field()}}


				<input type="hidden" name="work_day_id" id="work_day_id">
				<input type="hidden" name="task_id" id="task_id">
				<input type="hidden" name="phase_id" id="phase_id">
				<input type="hidden" name="activity_id" id="activity_id">

				<div class="modal-custom-detail style-ids" id="work_day_id"></div>
				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
					        data-dismiss="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
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

{{--MODAL EDIT --}}
@if(isset($userTime))
	<div class="modal" id="editTime" tabindex="-1" role="dialog" aria-labelledby="Éditer une absence">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{trans('work_days.act_update_hours')}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>

				<form id="wday_update" action="{{route('wday.update')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('put')}}
					{{csrf_field()}}

					<input type="hidden" name="work_day_id" id="work_day_id">
					<input type="hidden" name="task_id" id="task_id">
					<input type="hidden" name="phase_id" id="phase_id">
					<input type="hidden" name="activity_id" id="activity_id">

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

					<div class="modal-custom-detail style-ids" id="work_day_id"></div>
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
@endif
