{{--MODAL CREATE ACTIVITY--}}
<div class="modal" id="createActivity" tabindex="-1" role="dialog" aria-labelledby="Créer une activité">
	<div class="modal-dialog modal-large" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.add'))}}</div>
			<div class="modal-custom-libelle">{{trans('app.activity')}}</div>

			<form id="activity_create" action="{{route('activities.create')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{csrf_field()}}
				@include('activities.activity_create')

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

{{--MODAL UPDATE ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="updateActivity" tabindex="-1" role="dialog" aria-labelledby="Éditer une activité">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.Edit'))}}</div>
				<div class="modal-custom-libelle" id="activity_name"></div>

				<form id="activity_update" action="{{route('activities.update')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate autocomplete="off">
					{{method_field('put')}}
					{{csrf_field()}}
					<input type="hidden" name="activity_id" id="activity_id" value="">
					@include('activities.activity_create')

					<div class="modal-custom-detail style-ids" id="activity_id"></div>

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

{{--MODAL DELETE ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="deleteActivity" tabindex="-1" role="dialog" aria-labelledby="Supprimer une activité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('activity.Delete'))}}</div>
				<div class="modal-custom-libelle" id="activity_name"></div>
				<div class="modal-custom-description">{{trans('activity.ConfirmDelete')}}</div>
				<div class="modal-custom-description">{{trans('activity.ConfirmDelete2')}}</div>
				<div class="modal-custom-description show-warning-fatal">{{trans('app.nowayback')}}</div>

				<form action="{{route('activities.destroy')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('delete')}}
					{{csrf_field()}}
					<input type="hidden" name="activity_id" id="activity_id">

					<div class="modal-custom-detail style-ids" id="activity_id"></div>

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

{{--MODAL DETAILS ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="detailsActivity" tabindex="-1" role="dialog"
	     aria-labelledby="Afficher la vue simplifiée d'une activité">
		<div class="modal-dialog modal-largest" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">Vue simplifiée</div>
				<div class="modal-custom-libelle" id="activity_name"></div>

				<div class="flex-row flex-wrap-no justify-center items-center text-center pad-v-small marg-none modal-custom-libelle-ex has-search-sticky">
					<i class="fas fa-magic svg-inside-details"></i>
					<input class="form-control universal-filter" id="activitiesDetailsInput" type="text"
					       placeholder="Filtre universel" tabindex="1">
					<i class="modal-custom-btn-horizontal" tabindex="2"
					   title="{{trans('app.back')}}"
					   data-dismiss="modal"
					   data-toggle="tooltip"
					   data-placement="bottom">
						<i class="far fa-times-circle"></i>
					</i>
				</div>

				<form action="{{route('activities.details', $activity->activity_id)}}" method="get"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{csrf_field()}}
					@include('activities.activity_detail')

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

{{--MODAL TERMINATE ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="terminateActivity" tabindex="-1" role="dialog" aria-labelledby="Terminer une activité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('activity.Term'))}}</div>
				<div class="modal-custom-libelle" id="activity_name"></div>
				<div class="modal-custom-description">{{trans('activity.Terminate')}}</div>
				<div class="modal-custom-description">{{trans('activity.Terminate2')}}</div>
				<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

				<form action="{{route('activities.terminate')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<input type="hidden" name="activity_id" id="activity_id">

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

{{--MODAL ACTIVATE ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="activateActivity" tabindex="-1" role="dialog" aria-labelledby="Réactiver une activité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('activity.Act'))}}</div>
				<div class="modal-custom-libelle" id="activity_name"></div>
				<div class="modal-custom-description">{{trans('activity.Activate')}}</div>

				<form action="{{route('activities.activate')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<input type="hidden" name="activity_id" id="activity_id">

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

{{--CHANGE PRIVACY ACTIVITY--}}
@if(isset($activity))
	<div class="modal" id="privacyActivity" tabindex="-1" role="dialog"
	     aria-labelledby="Changer la visibilité d'une activité">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('activity.Privacy'))}}</div>
				<div class="modal-custom-libelle" id="activity_name"></div>
				<div class="modal-custom-description">
					Pour prévenir la création d'une tâche dans une activité (hors CPI, chef de service et
					directeur), marquer cette dernière comme étant 'privée'.
					<br><br><br>
					Par défaut une activité est <b>publique</b> (cadenas vert) : tout le monde pourra y créer une tâche.
					<br>
					Si l'activité est <b>privée</b> (cadenas rouge) : seuls le responsable, le chef de service et le
					directeur pourront y créer une tâche.
					<br><br><br>
					<u>Note</u> : si l'activité est privée, aucune phase ne sera accessible à la création d'une tâche
					et ce, quelque soit la visibilité des phases (hors profils cités plus haut).
					<br><br>
					<u>Note</u> : si l'activité est publique, seules les phases non privées seront éligibles à la
					création de tâche par tout le monde.
				</div>

				<form action="{{route('activities.privacy')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<input type="hidden" name="activity_id" id="activity_id">

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

{{--MODAL DOWNLOAD--}}
@if(isset($activity))
	<div class="modal" id="exportTables" tabindex="-1" role="dialog" aria-labelledby="Exporter">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="flex-col flex-wrap-no justify-center">
					<div class="modal-custom-title">{{ucfirst(trans('app.export'))}}</div>
					<div class="modal-custom-libelle">{{trans('app.desc_export')}}</div>

					@if(auth()->user()->role_id == config('constants.role_admin_id') ||
									auth()->user()->role_id == config('constants.role_directeur_id') ||
									auth()->user()->role_id == config('constants.role_service_id') )
						<form action="{{url('/download')}}" method="get"
						      class="needs-validation hide-submit modal-custom-form" novalidate>
							{{method_field('get')}}
							{{csrf_field()}}

							<div class="flex-row flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">
								<div class="flex-col flex-wrap-no justify-center text-center width-rem-15 pad-v-small">
									<input type="text" id="export_item" name="export_item" value="activities" hidden>
									<label class="flex-row flex-wrap-yes justify-evenly global-modal-label control-label"
									       for="type_activities">{{trans('app.activities')}}</label>
									<select class="form-control modal-custom-fields-input" name="type_activities"
									        id="type_activities">
										<option value="0">{{ucfirst(trans('app.all_wom_pls'))}}</option>
										<option value="1">{{ucfirst(trans('app.active_wom_pls'))}}</option>
									</select>
								</div>

								<div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-5 pad-v-small">
									<button class="modal-custom-btn-horizontal" tabindex="101"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        title="{{trans('app.ok')}}">
										<i class="far fa-check-circle"></i>
									</button>
								</div>
							</div>
						</form>


						<div class="config-separator ln-b-dashed-small-fonce"></div>

						<form id="charges_export" action="{{url('/download')}}" method="get"
						      class="needs-validation hide-submit modal-custom-form" novalidate>
							{{method_field('get')}}
							{{csrf_field()}}

							<div class="flex-row flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">
								<div class="flex-col flex-wrap-no justify-center text-center width-rem-15 pad-v-small">
									<input type="text" id="export_item" name="export_item" value="charges" hidden>
									<label class="flex-row flex-wrap-yes justify-evenly global-modal-label control-label"
									       for="type_charges">{{trans('app.charges')}}</label>
									<select class="form-control modal-custom-fields-input" name="type_charges"
									        id="type_charges">
										<option value="0">{{ucfirst(trans('app.all_wom_pls'))}}</option>
										<option value="1">{{ucfirst(trans('app.period'))}}</option>
									</select>
									<div class="add-hours-readonly-label pad-v-top-small" id="is_dates">
										<label class="flex-row flex-wrap-yes justify-evenly control-label"
										       for="charge_date_start">{{ucfirst(trans('app.lab_start'))}}</label>
										<input type="date" class="modal-custom-fields-input form-control"
										       id="charge_date_start"
										       name="charge_date_start">
										<label class="flex-row flex-wrap-yes justify-evenly control-label"
										       for="charge_date_end">{{ucfirst(trans('app.lab_end'))}}</label>
										<input type="date" class="modal-custom-fields-input form-control"
										       id="charge_date_end"
										       name="charge_date_end">
										<div id="date_error_gap" class="show-warning-small">Fin > Début</div>

									</div>
								</div>

								<div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-5 pad-v-small">
									<button class="modal-custom-btn-horizontal" tabindex="101"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        title="{{trans('app.ok')}}">
										<i class="far fa-check-circle"></i>
									</button>
								</div>
							</div>
						</form>

						<div class="config-separator ln-b-dashed-small-fonce"></div>
					@endif
					<form id="personal_export" action="{{url('/download')}}" method="get"
					      class="needs-validation hide-submit modal-custom-form" novalidate>
						{{method_field('get')}}
						{{csrf_field()}}

						<div class="flex-row flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">
							<div class="flex-col flex-wrap-no justify-center text-center width-rem-15 pad-v-small">
								<input type="text" id="export_item" name="export_item" value="personal" hidden>
								<label class="flex-row flex-wrap-yes justify-evenly global-modal-label control-label"
								       for="type_charges">{{ucfirst(trans('app.personal'))}}</label>
								<select class="form-control modal-custom-fields-input" name="type_personal"
								        id="type_personal">
									<option value="0">{{ucfirst(trans('app.all_wom_pls'))}}</option>
									<option value="1">{{ucfirst(trans('app.period'))}}</option>
								</select>
								<div class="add-hours-readonly-label pad-v-top-small" id="is_dates_personal">
									<label class="flex-row flex-wrap-yes justify-evenly control-label"
									       for="personal_date_start">{{ucfirst(trans('app.lab_start'))}}</label>
									<input type="date" class="modal-custom-fields-input form-control"
									       id="personal_date_start"
									       name="personal_date_start">
									<label class="flex-row flex-wrap-yes justify-evenly control-label"
									       for="personal_date_end">{{ucfirst(trans('app.lab_end'))}}</label>
									<input type="date" class="modal-custom-fields-input form-control"
									       id="personal_date_end"
									       name="personal_date_end">
									<div id="date_error_gap_personal" class="show-warning-small">Fin > Début</div>

								</div>
							</div>

							<div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-5 pad-v-small">
								<button class="modal-custom-btn-horizontal" tabindex="101"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        title="{{trans('app.ok')}}">
									<i class="far fa-check-circle"></i>
								</button>
							</div>
						</div>
					</form>

					<div class="config-separator"></div>

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
						        data-dismiss="modal"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i>
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>
@endif