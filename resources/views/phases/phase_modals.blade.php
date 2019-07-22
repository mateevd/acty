{{--MODAL CREATE PHASE--}}
<div class="modal" id="createPhase" tabindex="-1" role="dialog" aria-labelledby="Créer une phase">
	<div class="modal-dialog modal-large" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.add'))}}</div>
			<div class="modal-custom-libelle">{{trans('phase.lab_phase')}}</div>

			<form id="phase_create" action="{{route('phases.create', $activity->activity_id)}}" method="post"
				  class="needs-validation hide-submit modal-custom-form" novalidate>
				{{csrf_field()}}

				@include('phases.phase_form')

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

{{--MODAL EDIT PHASE--}}
@if(isset($phase))
	<div class="modal" id="editPhase" tabindex="-1" role="dialog" aria-labelledby="Éditer une phase">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.Edit'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>

				<form  id="phase_update" action="{{route('phases.update', $activity->activity_id)}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('PUT')}}
					{{csrf_field()}}

					@include('phases.phase_form')
					<div class="modal-custom-detail style-ids" id="phase_id"></div>

					<input type="hidden" name="phase_id" id="phase_id">
					<input type="hidden" name="activity_id" id="activity_id">

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

{{--MODAL CHANGE PRIVACY--}}
@if(isset($phase))
	<div class="modal" id="privacyPhase" tabindex="-1" role="dialog"
		 aria-labelledby="Changer la visibilité d'une phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.privacy'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>
				<div class="modal-custom-description">
					Pour prévenir la création d'une tâche dans une phase (hors CPI, chef de service et
					directeur), marquer cette dernière comme étant 'privée'.
					<br><br><br>
					Par défaut une phase est <b>publique</b> (cadenas vert) : tout le monde pourra y créer une tâche.
					<br>
					Si la phase est <b>privée</b> (cadenas rouge) : seuls le CPI, le chef de service et le directeur
					pourront y créer une tâche.
					<br><br><br>
					<u>Note</u> : si l'activité est privée, aucune phase ne sera accessible à la création d'une tâche et
					ce, quelque soit la visibilité des phases (hors profils cités plus haut).
					<br><br>
					<u>Note</u> : si l'activité est publique, seules les phases non privées seront éligibles à la création
					de tâche par tout le monde.
				</div>

				<form action="{{route('phases.privacy')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<div class="modal-custom-detail style-ids" id="phase_id"></div>

					<input type="hidden" name="phase_id" id="phase_id">

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

{{--MODAL TERMINATE PHASE--}}
@if(isset($phase))
	<div class="modal" id="terminatePhase" tabindex="-1" role="dialog" aria-labelledby="Terminer une phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.terminate'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>
				<div class="modal-custom-description">{{trans('phase.act_terminate_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

				<form action="{{route('phases.terminate')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form">
					{{method_field('post')}}
					{{csrf_field()}}

					<div class="modal-custom-detail style-ids" id="phase_id"></div>

					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL ACTIVATE PHASE--}}
@if(isset($phase))
	<div class="modal" id="activatePhase" tabindex="-1" role="dialog" aria-labelledby="Réactiver une phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>
				<div class="modal-custom-description">{{trans('phase.act_activate_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

				<form action="{{route('phases.activate')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form">
					{{method_field('post')}}
					{{csrf_field()}}

					<div class="modal-custom-detail style-ids" id="phase_id"></div>

					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL DELETE PHASE--}}
@if(isset($phase))
	<div class="modal" id="deletePhase" tabindex="-1" role="dialog" aria-labelledby="Supprimer une phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.delete'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>
				<div class="modal-custom-description">{{trans('phase.act_delete_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('phase.act_delete_confirm')}}</div>
				<div class="modal-custom-description show-warning-fatal">{{trans('app.nowayback')}}</div>

				<form action="{{route('phases.destroy')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form">
					{{method_field('delete')}}
					{{csrf_field()}}
					
					<div class="modal-custom-detail style-ids" id="phase_id"></div>

					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL MOVE PHASE--}}
@if(isset($phase))
	<div class="modal" id="movePhase" tabindex="-1" role="dialog" aria-labelledby="Déplacer une phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('phase.act_move'))}}</div>
				<div class="modal-custom-libelle" id="phase_name"></div>
				<div class="modal-custom-description">{{trans('phase.act_move_desc_01')}}</div>

				<form id="phase_move" action="{{route('phases.movePhase')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="phase_id" id="phase_id" value="">

					<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small">
						<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-small align-stretch">
							<div class="flex-col flex-wrap-no justify-flex-end text-center">
								{{ Form::label("activity_id", trans('app.activity'),["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label"]) }}
								{{ Form::select("activity_id", $activities_list, null, ["class"=>"form-control modal-custom-fields-input", 'placeholder'=>trans(' '), 'required', 'tabindex'=>'1']) }}
								<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_activity')}}</div>
							</div>
						</div>
					</div>

					<div class="modal-custom-description marg-v-top-small">{{trans('phase.act_move_confirm')}}</div>
					
					<div class="modal-custom-detail style-ids" id="phase_id"></div>

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