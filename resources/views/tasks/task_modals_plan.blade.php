{{--MODAL CREATE TASK--}}
	<div class="modal createTask" id="createTaskOccurence" tabindex="-1" role="dialog"
		 aria-labelledby="Ajouter une tâche" data-keyboard="false">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.add'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.lab_task')}}</div>

				<form id="task_create" action="{{route('tasks.create')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{csrf_field()}}

					@include('tasks.task_form_create')

					<input type="hidden" name="activity_id" id="activity_id" value="">
					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="user_id_values" id="user_id_values" value="">
					<input type="hidden" name="public" id="public" value="0">

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.ok')}}">
							<i class="far fa-check-circle"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>

{{--MODAL UPDATE TASK--}}
@if(isset($phase))
	<div class="modal" id="editTask" tabindex="-1" role="dialog" aria-labelledby="Éditer une tâche">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.Edit'))}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>

				<form id="task_update" action="{{route('tasks.update', $phase->phase_id)}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('PUT')}}
					{{csrf_field()}}

					@include('tasks.task_form_edit')

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

					<div class="modal-custom-detail style-ids" id="task_id"></div>

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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

{{--MODAL COPY TASK--}}
@if(isset($phase))
	<div class="modal" id="copySingleTask" tabindex="-1" role="dialog"
		 aria-labelledby="Copier une tâche dans la même phase">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.copy'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_copy_single')}}</div>
				<div class="modal-custom-description">{{trans('task.act_copy_desc_01')}}</div>
				<div class="modal-custom-description-ex">{{trans('task.act_copy_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('task.act_copy_desc_03')}}</div>

				<form action="{{route('tasks.copy')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<div class="modal-custom-detail style-ids" id="task_id"></div>

					<input type="hidden" name="task_id" id="task_id">

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal apply-spin" tabindex="101"
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

{{--MODAL DELETE TASK--}}
@if(isset($phase))
	<div class="modal" id="deleteTask" tabindex="-1" role="dialog" aria-labelledby="Supprimer une tâche">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.delete'))}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>
				<div class="modal-custom-description">{{trans('task.act_delete_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_delete_confirm')}}</div>
				<div class="modal-custom-description show-warning-fatal">{{trans('app.nowayback')}}</div>

				<form action="{{route('tasks.destroy')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('delete')}}
					{{csrf_field()}}
					<div class="modal-custom-detail style-ids" id="task_id"></div>

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL ACTIVATE TASK--}}
@if(isset($phase))
	<div class="modal" id="activateTask" tabindex="-1" role="dialog" aria-labelledby="Réactiver une tâche">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>
				<div class="modal-custom-description">{{trans('task.act_activate_desc_01')}}</div>
				@if (session()->get('cra_validate') == true) <div class="modal-custom-description">{{trans('task.act_activate_desc_02')}}</div> @endif
				<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

				<form action="{{route('tasks.activate')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<div class="modal-custom-detail style-ids" id="task_id"></div>

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL TERMINATE TASK FOR PLAN PAGE--}}
@if(isset($phase))
	<div class="modal" id="terminateTask" tabindex="-1" role="dialog" aria-labelledby="Terminer une tâche">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.terminate'))}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>
				<div class="modal-custom-description">{{trans('task.act_terminate_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_terminate_desc_02')}}</div>
				<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

				<form action="{{route('tasks.terminate')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<div class="modal-custom-detail style-ids" id="task_id"></div>

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="phase_id" id="phase_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL DELETE MULTI TASK--}}
@if(isset($phase))
	<div class="modal" id="deleteMultiTask" tabindex="-1" role="dialog" aria-labelledby="Supprimer plusieurs tâches">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.delete'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_delete_multi')}}</div>
				<div class="modal-custom-description">{{trans('task.act_delete_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_delete_multi_confirm')}}</div>
				<div class="modal-custom-description show-warning-fatal">{{trans('app.nowayback')}}</div>

				<form action="{{route('tasks.destroy')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('delete')}}
					{{csrf_field()}}
					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

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

{{--MODAL MOVE MULTI TASK--}}
@if(isset($phase))
	<div class="modal" id="moveMultiTask" tabindex="-1" role="dialog" aria-labelledby="Déplacer plusieurs tâches">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.move'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_move_multi')}}</div>
				<div class="modal-custom-description">{{trans('task.act_move_desc')}}</div>

				<form id="task_move_multi" action="{{route('tasks.moveMultiTask')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<input type="hidden" name="task_id" id="task_id" value="">

					<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small">
						<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-small align-stretch">
							<div class="flex-col flex-wrap-no justify-flex-end text-center">
								{{ Form::label("activity_id_move", trans('app.activity'),["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label"]) }}
								{{ Form::select("activity_id_move", $activities_list, null, ["id" => "task_activities_list", "class"=>"form-control modal-custom-fields-input", 'placeholder'=>trans(' '), 'required']) }}
								<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_activity')}}</div>
							</div>
						</div>
						<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-small align-stretch">
							<div class="flex-col flex-wrap-no justify-flex-end text-center">
								{{ Form::label("phase_id_move", trans('activity.lab_phase'), ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label"]) }}
								{{ Form::select("phase_id_move", isset($phases_list) ? $phases_list : [], null, ["id" => "task_phases_list", "class"=>"form-control modal-custom-fields-input",'placeholder'=>trans(' '), 'required']) }}
								<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_phase')}}</div>
							</div>
						</div>
					</div>

					<div class="modal-custom-description marg-v-top-small">{{trans('task.act_move_multi_confirm')}}</div>

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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

{{--MODAL COPY MULTI TASK--}}
@if(isset($phase))
	<div class="modal" id="copyMultiTask" tabindex="-1" role="dialog" aria-labelledby="Copier plusieurs tâches">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.copy'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_copy_multi')}}</div>
				<div class="modal-custom-description">{{trans('task.act_copy_desc_multi_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_copy_desc_multi_02')}}</div>
				<div class="modal-custom-description">{{trans('task.act_copy_desc_multi_03')}}</div>

				<form id="task_copy_multi" action="{{route('tasks.copyMultiTask')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}
					<input type="hidden" name="task_id" id="task_id" value="">

					<div class="flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small">
						<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-small align-stretch">
							<div class="flex-col flex-wrap-no justify-flex-end text-center">
								{{ Form::label("activity_id_copy", trans('app.activity'),["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label"]) }}
								{{ Form::select("activity_id_copy", $activities_list, null, ["id" => "task_activities_list2", "class"=>"form-control modal-custom-fields-input", 'placeholder'=>trans(' '), 'required']) }}
								<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_activity')}}</div>
							</div>
						</div>
						<div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-small align-stretch">
							<div class="flex-col flex-wrap-no justify-flex-end text-center">
								{{ Form::label("phase_id_copy", trans('activity.lab_phase'), ["class"=>"flex-row flex-wrap-yes justify-evenly pad-h-medium pad-v-small global-modal-label"]) }}
								{{ Form::select("phase_id_copy", isset($phases_list) ? $phases_list : [], null, ["id" => "task_phases_list2", "class"=>"form-control modal-custom-fields-input",'placeholder'=>trans(' '), 'required']) }}
								<div class="invalid-feedback show-warning-small">{{trans('app.mandatory_phase')}}</div>
							</div>
						</div>
					</div>

					<div class="modal-custom-description marg-v-top-small">{{trans('task.act_copy_multi_confirm')}}</div>

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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

{{--MODAL TERMINATE MULTI TASK--}}
@if(isset($phase))
	<div class="modal" id="terminateMultiTask" tabindex="-1" role="dialog" aria-labelledby="Terminer plusieurs tâches">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.terminate'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_terminate_multi')}}</div>
				<div class="modal-custom-description">{{trans('task.act_terminate_multi_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_terminate_multi_confirm')}}</div>

				<form id="task_terminate_multi" action="{{route('tasks.terminate')}}" method="post"
					  class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
								data-dismiss="modal"
								data-toggle="tooltip"
								data-placement="bottom"
								title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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

{{--MODAL ACTIVATE MULTI TASK--}}
@if(isset($phase))
	<div class="modal" id="activateMultiTask" tabindex="-1" role="dialog" aria-labelledby="Activer plusieurs tâches">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
				<div class="modal-custom-libelle">{{trans('task.act_activate_multi')}}</div>
				<div class="modal-custom-description">{{trans('task.act_activate_multi_desc_01')}}</div>
				<div class="modal-custom-description">{{trans('task.act_activate_multi_confirm')}}</div>

				<form id="task_activate_multi" action="{{route('tasks.activate')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="task_id" id="task_id" value="">
					<input type="hidden" name="activity_id" id="activity_id" value="">

					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
						        data-dismiss="modal"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i></button>
						<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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