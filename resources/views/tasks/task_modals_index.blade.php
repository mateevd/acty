{{--MODAL EDIT CURRENT TASK FOR TASKS PAGE--}}
@if(isset($currentTask))
	<div class="modal" id="editTask" tabindex="-1" role="dialog" aria-labelledby="Éditer une tâche">
		<div class="modal-dialog modal-large" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.Edit'))}}</div>
				<div class="modal-custom-libelle" id="task_name"></div>

				<form id="task_update"
				      @if($currentTask->phase_id)
				      action="{{route('tasks.update', $currentTask->phase_id)}}"
				      @else
				      action="{{route('tasks.update', $oldTask->phase_id)}}"
				      @endif
				      method="post"
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

{{--MODAL TERMINATE TASK FOR TASKS PAGE--}}
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

{{-- MODAL TERMINATE ALL CURRENT TASKS--}}
<div class="modal" id="terminateAllCurrentTask" tabindex="-1" role="dialog"
     aria-labelledby="Terminer toutes les tâches du mois">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.terminate'))}}</div>
			<div class="modal-custom-libelle">{{trans('task.act_terminate_all')}}</div>
			<div class="modal-custom-description">{{trans('task.act_terminate_desc_01')}}</div>
			<div class="modal-custom-description">{{trans('task.act_terminate_desc_02')}}</div>
			<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

			<form action="{{route('tasks.terminate')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				@foreach($currentTasks as $currentTask)
					<input type="hidden" value="{{$currentTask->task_id}}" name="task_id[]"/>
				@endforeach

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

{{-- MODAL TERMINATE ALL OLD TASKS--}}
<div class="modal" id="terminateAllOldTask" tabindex="-1" role="dialog"
     aria-labelledby="Terminer toutes les tâches du mois">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.terminate'))}}</div>
			<div class="modal-custom-libelle">{{trans('task.act_terminate_all')}}</div>
			<div class="modal-custom-description">{{trans('task.act_terminate_desc_01')}}</div>
			<div class="modal-custom-description">{{trans('task.act_terminate_desc_02')}}</div>
			<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

			<form action="{{route('tasks.terminate')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				@foreach($oldTasks as $oldTask)
					<input type="hidden" value="{{$oldTask->task_id}}" name="task_id[]"/>
				@endforeach

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

{{-- MODAL REACTIVATE ALL CURRENT TASKS--}}
<div class="modal" id="activateAllCurrentTask" tabindex="-1" role="dialog"
     aria-labelledby="Terminer toutes les tâches du mois">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
			<div class="modal-custom-libelle">{{trans('task.act_activate_all')}}</div>
			<div class="modal-custom-description">{{trans('task.act_activate_desc_01')}}</div>
			<div class="modal-custom-description">{{trans('task.act_activate_desc_02')}}</div>
			<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

			<form action="{{route('tasks.activate')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				@foreach($currentTasks as $currentTask)
					<input type="hidden" value="{{$currentTask->task_id}}" name="task_id[]"/>
				@endforeach

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

{{-- MODAL REACTIVATE ALL OLD TASKS--}}
<div class="modal" id="activateAllOldTask" tabindex="-1" role="dialog"
     aria-labelledby="Terminer toutes les tâches du mois">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
			<div class="modal-custom-libelle">{{trans('task.act_activate_all')}}</div>
			<div class="modal-custom-description">{{trans('task.act_activate_desc_01')}}</div>
			<div class="modal-custom-description">{{trans('task.act_activate_desc_02')}}</div>
			<div class="modal-custom-description">{{trans('app.act_action_confirm')}}</div>

			<form action="{{route('tasks.activate')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{method_field('post')}}
				{{csrf_field()}}

				@foreach($oldTasks as $oldTask)
					<input type="hidden" value="{{$oldTask->task_id}}" name="task_id[]"/>
				@endforeach

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

{{--MODAL REACTIVATE TASK FOR TASKS INDEX PAGE--}}
<div class="modal" id="activateTask" tabindex="-1" role="dialog" aria-labelledby="Réactiver une tâche">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.activate'))}}</div>
			<div class="modal-custom-libelle" id="task_name"></div>
			<div class="modal-custom-description">{{trans('task.act_activate_desc_01')}}</div>
			@if (session()->get('cra_validate') == true)
				<div class="modal-custom-description">{{trans('task.act_activate_desc_02')}}</div> @endif
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

{{--MODAL CREATE PUBLIC TASK--}}
<div class="modal" id="createTaskPublic" tabindex="-1" role="dialog" aria-labelledby="Créer une tâche publique">
	<div class="modal-dialog modal-large" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{ucfirst(trans('app.add'))}}</div>
			<div class="modal-custom-libelle">{{trans('task.act_create')}}</div>

			<form id="task_create_public" action="{{route('tasks.create')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate autocomplete="off">
				{{csrf_field()}}

				@include('tasks.task_form_create_public')
				<input type="hidden" name="public" id="public" value="1">

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