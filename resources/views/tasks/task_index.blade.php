@extends('layouts.app')

@section('content')

	@if(session()->has('flashy_notification.message'))
		<script id="flashy-template" type="text/template">
			<div class="flashy flashy--{{ session()->get('flashy_notification.type') }}">
				<i class="flashy__body"></i>
			</div>
		</script>
	@endif

	{{--SELECT TAB AND PERIOD--}}
	<div class="navbar-tabs">
		<div class="navbar-tabs-select-tab">
			<ul class="nav" role="tablist" id="tasks-tab-selection">
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#tasks_user" role="tab" data-toggle="tab">
						<div>Mes tâches</div>
						<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
							)</span>
					</a>
				</li>
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#tasks_user_before" role="tab"
					   data-toggle="tab">
						<div>Mes tâches</div>
						<span class="exp-annee">({{trans('app.anterieur_wom_pls')}})</span>
					</a>
				</li>
				@if(auth()->user()->role_id == config('constants.role_admin_id') ||
				auth()->user()->role_id == config('constants.role_directeur_id') ||
				auth()->user()->role_id == config('constants.role_service_id'))
					<li class="pad-h-right-small">
						<a class="flex-row flex-wrap-no nav-link-period" href="#tasks_entity" role="tab"
						   data-toggle="tab">
							<div>
								@if(auth()->user()->role_id == config('constants.role_service_id')) {{ucfirst(trans('app.service'))}} @endif
								@if(auth()->user()->role_id == config('constants.role_directeur_id')) {{ucfirst(trans('app.department'))}} @endif
								@if(auth()->user()->role_id == config('constants.role_admin_id')) {{ucfirst(trans('app.department'))}} @endif
							</div>
							<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>
						</a>
					</li>
				@endif
			</ul>
		</div>
		<div class="navbar-tabs-select-date">
			<form id="date_change" action="{{route('tâches')}}" method="get" name="dateSelect" class="hide-submit">
				<div class="">
					{!! Form::selectMonth('monthSelect', $current_date->month ? $current_date->month : Carbon\Carbon::now()->month ,
							["class"=>"drop-date-common drop-date-month"])!!}
				</div>
				<div class="">
					{!! Form::selectRange('yearSelect', config('constants.start_year'), config('constants.end_year'), $current_date->year ? $current_date->year : Carbon\Carbon::now()->year,
							["class"=>"drop-date-common drop-date-year"])!!}
				</div>
				<div class="drop-date-submit">
					<button id="btn-submit-form-date" class="drop-date-custom-btn"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.ok')}}">
						<i class="fas fa-arrow-circle-right"></i>
					</button>
				</div>
			</form>
		</div>
	</div>

	<div class="tab-content">
		{{--SAISIE DU MOIS--}}
		<div class="tab-pane" role="tabpanel" id="tasks_user">

			<div class="flex-row flex-wrap-no justify-between items-center default-entity-title"
			     role="tablist">
				<div class="flex-row flex-wrap-no justify-between items-center flex-grow-no pad-h-small pad-v-top-medium pad-v-bottom-small">
					<div class="flex-row flex-wrap-no items-center action-btn-group-cra justify-evenly">
						<div class="flex-row flex-wrap-no items-center action-btn-cra justify-evenly">
						</div>
					</div>
				</div>
				<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-1 pad-h-small pad-v-top-medium pad-v-bottom-small">
					<span class="default-entity-title-data">
						{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}{{' '}}
					</span>
					<span class="default-entity-title-label italic">
					@if(auth()->user()->role_id == config('constants.role_admin_id'))
						({{ucfirst(config('constants.role_admin'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_directeur_id'))
						({{ucfirst(config('constants.role_directeur'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_service_id'))
						({{ucfirst(config('constants.role_service'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_projet_id'))
						({{ucfirst(config('constants.role_projet'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_agent_id'))
						({{ucfirst(config('constants.role_agent'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_prestataire_id'))
						({{ucfirst(config('constants.role_prestataire'))}})@endif
					</span>
				</div>
				<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">J.ouvrés <span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span> : </span>
						<span class="default-entity-title-data">{{number_format($open_days_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Absences <span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span> : </span>
						<span class="default-entity-title-data style-absence">{{number_format($user_total_absence_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Prévu <span class="exp-annee">(affiché)</span> : </span>
						<span class="default-entity-title-data style-prevu-total">{{number_format($user_total_prevu_total_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Réalisé <span
									class="exp-annee">(affiché)</span> : </span>
						<span class="default-entity-title-data style-realise">{{number_format($user_total_realise, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Réalisé <span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span> : </span>
						<span class="default-entity-title-data style-realise-light no-bold">{{number_format($user_total_realise_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Restant <span
									class="exp-annee">(affiché)</span> : </span>
						<span class="default-entity-title-data style-prevu">{{number_format($user_total_restant_month, '3', ',', ' ')}}</span>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th data-defaultsort="disabled" class="text-center">
							<div class="action-btn-add-hours justify-center">

								{{--CREATE TASK PUBLIC--}}
								<button class="btn-common "
								        title="{{trans('task.tool_create_public')}}"
								        name="createTaskPublic"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#createTaskPublic">
									<i class="far fa-calendar-plus svg-large btn-theme-fonce-leger"></i>
								</button>

								{{--TERMINATE ALL TASKS--}}
								<button class="btn-common "
								        title="{{trans('task.act_terminate_all')}}"
								        name="terminateAllTask"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#terminateAllCurrentTask">
									<i class="fas fa-check-double svg-large btn-theme-fonce-leger"></i>
								</button>

								{{--REACTIVATE ALL TASKS--}}
								<button class="btn-common "
								        title="{{trans('task.act_activate_all')}}"
								        name="activateAllTask"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#activateAllCurrentTask">
									<i class="fas fa-undo svg-large btn-theme-fonce-leger"></i>
								</button>
							</div>
						</th>
						<th data-defaultsort="asc">
							{{trans('activity.Name')}}
						</th>
						<th class="text-center tiny-cell">
							{{trans('activity.Manager')}}
						</th>
						<th>
							{{trans('phase.Phase')}}
						</th>
						<th>
							{{trans('task.NameFull')}}
						</th>
						<th>
							{{trans('task.Type')}}
						</th>
						<th class="text-center">
							{{'Mois'}}
						</th>
						<th class="text-right tiny-cell">
							{{trans('task.lab_days')}}
						</th>
						<th class="text-right tiny-cell">
							{{trans('task.DaysR')}}
						</th>
						<th class="text-right italic tiny-cell">
							{{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
						</th>
					</tr>
					</thead>
					<tbody id="filter_table">
					@foreach($currentTasks as $currentTask)
						<tr class="action-btn-cell
						@if($currentTask->task_status == config('constants.status_terminated')) tr-task-terminated @endif
						@if($currentTask->task_status == config('constants.status_not_validated')) tr-task-not-validated @endif
						@if($currentTask->task_status == config('constants.status_validated')) tr-task-validated @endif ">
							<td class="action-btn-item">
								<div class="action-btn-group justify-evenly">

									{{--EDIT Current TASK--}}
									<button id="editTaskButton"
									        class="btn-common"
									        title="{{ucfirst(trans('app.Edit'))}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task=" {{json_encode($currentTask)}} "
									        data-phase_id=" {{json_encode($currentTask->phase_id)}} "
									        data-activity_id=" {{json_encode($currentTask->activity_id)}} "
											@if(auth()->user()->role_id == config('constants.role_admin_id')
											|| auth()->user()->role_id == config('constants.role_directeur_id')
											|| auth()->user()->role_id == config('constants.role_service_id')
											|| $currentTask->activity_manager == auth()->user()->id
											|| $currentTask->activity_deputy == auth()->user()->id)
											@else
												disabled
											@endif
											data-target="#editTask"
									        @if($currentTask->task_status != config('constants.status_active')) disabled @endif>
										<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
									</button>

									{{--DELETE TASK--}}
									<button id="deleteTaskButton"
									        class="btn-common"
									        title="{{trans('app.delete')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task_id=" {{json_encode($currentTask->task_id)}} "
									        data-task_name=" {{json_encode($currentTask->task_name)}} "
									        data-phase_id=" {{json_encode($currentTask->phase_id)}} "
									        data-activity_id=" {{json_encode($currentTask->activity_id)}} "
											@if(auth()->user()->role_id == config('constants.role_admin_id')
											|| auth()->user()->role_id == config('constants.role_directeur_id')
											|| auth()->user()->role_id == config('constants.role_service_id')
											|| $currentTask->activity_manager == auth()->user()->id
											|| $currentTask->activity_deputy == auth()->user()->id)
											@else
												disabled
											@endif
											data-target="#deleteTask">
										<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
									</button>

									{{--TERMINATE TASK--}}
									<button class="btn-common " id="terminateTaskButton"
									        title="{{trans('app.terminate')}}"
									        data-task_id=" {{json_encode($currentTask->task_id)}} "
									        data-task_name=" {{json_encode($currentTask->task_name)}} "
									        data-phase_id=" {{json_encode($currentTask->phase_id)}} "
									        data-activity_id=" {{json_encode($currentTask->activity_id)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        @if($currentTask->task_status != config('constants.status_active')) hidden
									        @endif
									        data-target="#terminateTask">
										<i class="fas fa-check svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ACTIVATE TASK--}}
									<button id="activateTaskButton"
									        class="btn-common"
									        title="{{trans('app.activate')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task_id=" {{json_encode($currentTask->task_id)}} "
									        data-task_name=" {{json_encode($currentTask->task_name)}} "
									        data-phase_id=" {{json_encode($currentTask->phase_id)}} "
									        data-activity_id=" {{json_encode($currentTask->activity_id)}} "
									        data-target="#activateTask"
									        @if ( 	$currentTask->task_status != config('constants.status_terminated') &&
													$currentTask->task_status != config('constants.status_not_validated') &&
													$currentTask->task_status != config('constants.status_validated') )
									        	hidden
									        	@endif >
										<i class="fas fa-undo svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ADD HOURS--}}
									<button class="btn-common " id="addHoursButton"
									        title="Ajouter des heures effectuées sur la tâche"
									        data-task_show=" {{json_encode($currentTask)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-target="#addHours"
									        @if($currentTask->task_status != config('constants.status_active')) disabled @endif>
										<i class="fas fa-business-time svg-small btn-theme-clair-fort"></i>
									</button>
								</div>
							</td>
							<td class="wrap-yes truncate-medium">{{$currentTask->activity_code}}</td>
							<td class="text-center">{{$currentTask->activity_manager_tri}}</td>
							<td class="wrap-yes truncate-large">{{$currentTask->phase_name}}</td>
							<td class="wrap-yes truncate-large style-tache">
								<i class="fas fa-flag
								@if($currentTask->task_milestone == 1) milestone-yes @else milestone-no @endif"></i>
								{{$currentTask->task_name}}
							</td>
							<td class="wrap-yes truncate-large">{{$currentTask->task_type_name}}</td>
							<td class="text-center wrap-yes truncate-small"
							    data-value="{{ $currentTask->task_start_p }}">{{Carbon\Carbon::parse($currentTask->task_start_p)->format('m/Y')}}</td>
							<td class="text-right style-prevu"
							    data-value="{{$currentTask->task_days_p}}">{{number_format($currentTask->task_days_p, '3', ',', ' ')}}</td>
							<td class="text-right style-realise"
							    data-value="{{$currentTask->task_days_r}}">{{number_format($currentTask->task_days_r, '3', ',', ' ')}}</td>
							<td class="text-right style-realise-light"
							    data-value="{{$currentTask->task_work_days_month}}">{{number_format($currentTask->task_work_days_month, '3', ',', ' ')}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>

			<div class="text-center table-separator"></div>

		</div>

		{{--SAISIE DES MOIS ANTÉRIEURS--}}
		<div class="tab-pane" role="tabpanel" id="tasks_user_before">

			<div class="flex-row flex-wrap-no justify-between items-center default-entity-title"
			     role="tablist">
				<div class="flex-row flex-wrap-no justify-between items-center flex-grow-no pad-h-small pad-v-top-medium pad-v-bottom-small">
					<div class="flex-row flex-wrap-no items-center action-btn-group-cra justify-evenly">
						<div class="flex-row flex-wrap-no items-center action-btn-cra justify-evenly">
						</div>
					</div>
				</div>
				<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-1 pad-h-small pad-v-top-medium pad-v-bottom-small">
					<span class="default-entity-title-data">
						{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}{{' '}}
					</span>
					<span class="default-entity-title-label italic">
					@if(auth()->user()->role_id == config('constants.role_admin_id'))
						({{ucfirst(config('constants.role_admin'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_directeur_id'))
						({{ucfirst(config('constants.role_directeur'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_service_id'))
						({{ucfirst(config('constants.role_service'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_projet_id'))
						({{ucfirst(config('constants.role_projet'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_agent_id'))
						({{ucfirst(config('constants.role_agent'))}})@endif
					@if(auth()->user()->role_id == config('constants.role_prestataire_id'))
						({{ucfirst(config('constants.role_prestataire'))}})@endif
					</span>
				</div>
				<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Absences <span
									class="exp-annee">({{trans('app.anterieur_wom_pls')}})</span> : </span>
						<span class="default-entity-title-data style-absence">{{number_format($user_total_absence_old, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Prévu <span
									class="exp-annee">({{trans('app.anterieur_man')}})</span> : </span>
						<span class="default-entity-title-data style-prevu-total">{{number_format($user_total_prevu_total_old, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Réalisé <span
									class="exp-annee">({{trans('app.anterieur_man')}})</span> : </span>
						<span class="default-entity-title-data style-realise">{{number_format($user_total_realise_old, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Restant <span
									class="exp-annee">({{trans('app.anterieur_man')}})</span> : </span>
						<span class="default-entity-title-data style-prevu">{{number_format($user_total_restant_old, '3', ',', ' ')}}</span>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th data-defaultsort="disabled" class="text-center">
							<div class="action-btn-add-hours justify-center">

								{{--CREATE TASK PUBLIC--}}
								<button class="btn-common "
								        title="{{trans('task.tool_create_public')}}"
								        name="createTaskPublic"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#createTaskPublic">
									<i class="far fa-calendar-plus svg-large btn-theme-fonce-leger"></i>
								</button>

								{{--TERMINATE ALL TASKS--}}
								<button class="btn-common "
								        title="{{trans('task.act_terminate_all')}}"
								        name="terminateAllTask"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#terminateAllOldTask">
									<i class="fas fa-check-double svg-large btn-theme-fonce-leger"></i>
								</button>

								{{--REACTIVATE ALL TASKS--}}
								<button class="btn-common "
								        title="{{trans('task.act_activate_all')}}"
								        name="activateAllTask"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#activateAllOldTask">
									<i class="fas fa-undo svg-large btn-theme-fonce-leger"></i>
								</button>
							</div>
						</th>
						<th>
							{{trans('activity.Name')}}
						</th>
						<th class="text-center tiny-cell">
							{{trans('activity.Manager')}}
						</th>
						<th>
							{{trans('phase.Phase')}}
						</th>
						<th>
							{{trans('task.NameFull')}}
						</th>
						<th>
							{{trans('task.Type')}}
						</th>
						<th class="text-center" data-defaultsort="asc">
							{{'Mois'}}
						</th>
						<th class="text-right tiny-cell">
							{{trans('task.lab_days')}}
						</th>
						<th class="text-right tiny-cell">
							{{trans('task.DaysR')}}
						</th>
					</tr>
					</thead>
					<tbody id="filter_table">
					@foreach($oldTasks as $oldTask)
						<tr class="
						@if($oldTask->task_status == config('constants.status_terminated')) tr-task-terminated @endif
						@if($oldTask->task_status == config('constants.status_not_validated')) tr-task-not-validated @endif
						@if($oldTask->task_status == config('constants.status_validated')) tr-task-validated @endif ">
							<td class="action-btn-item">
								<div class="action-btn-group justify-evenly">

									{{--EDIT TASK--}}
									<button id="editTaskButton"
									        class="btn-common"
									        title="{{ucfirst(trans('app.Edit'))}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task=" {{json_encode($oldTask)}} "
									        data-phase_id=" {{json_encode($oldTask->phase_id)}} "
									        data-activity_id=" {{json_encode($oldTask->activity_id)}} "
									        data-target="#editTask"
											@if(auth()->user()->role_id == config('constants.role_admin_id')
											|| auth()->user()->role_id == config('constants.role_directeur_id')
											|| auth()->user()->role_id == config('constants.role_service_id')
											|| $oldTask->activity_manager == auth()->user()->id
											|| $oldTask->activity_deputy == auth()->user()->id)
											@else
												disabled
											@endif
											@if($oldTask->task_status != config('constants.status_active')) disabled @endif>
										<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
									</button>

									{{--DELETE TASK--}}
									<button id="deleteTaskButton"
									        class="btn-common"
									        title="{{trans('app.delete')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task_id=" {{json_encode($oldTask->task_id)}} "
									        data-task_name=" {{json_encode($oldTask->task_name)}} "
									        data-phase_id=" {{json_encode($oldTask->phase_id)}} "
									        data-activity_id=" {{json_encode($oldTask->activity_id)}} "
											@if(auth()->user()->role_id == config('constants.role_admin_id')
											|| auth()->user()->role_id == config('constants.role_directeur_id')
											|| auth()->user()->role_id == config('constants.role_service_id')
											|| $oldTask->activity_manager == auth()->user()->id
											|| $oldTask->activity_deputy == auth()->user()->id)
											@else
												disabled
											@endif
											data-target="#deleteTask">
										<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
									</button>

									{{--TERMINATE TASK--}}
									<button class="btn-common " id="terminateTaskButton"
									        title="Terminer"
									        data-task_id=" {{json_encode($oldTask->task_id)}} "
									        data-task_name=" {{json_encode($oldTask->task_name)}} "
									        data-phase_id=" {{json_encode($oldTask->phase_id)}} "
									        data-activity_id=" {{json_encode($oldTask->activity_id)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-target="#terminateTask"
									        @if($oldTask->task_status != config('constants.status_active')) hidden @endif>
										<i class="fas fa-check svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ACTIVATE TASK--}}
									<button id="activateTaskButton"
									        class="btn-common"
									        title="{{trans('app.activate')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-task_id=" {{json_encode($oldTask->task_id)}} "
									        data-task_name=" {{json_encode($oldTask->task_name)}} "
									        data-phase_id=" {{json_encode($oldTask->phase_id)}} "
									        data-activity_id=" {{json_encode($oldTask->activity_id)}} "
									        data-target="#activateTask"
									        @if ( 	$oldTask->task_status != config('constants.status_terminated') &&
													$oldTask->task_status != config('constants.status_not_validated') &&
													$oldTask->task_status != config('constants.status_validated') )
									        	hidden
									        	@endif >
										<i class="fas fa-undo svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ADD HOURS--}}
									<button class="btn-common " id="addHoursButton"
									        title="Ajouter des heures effectuées sur la tâche"
									        data-task_show=" {{json_encode($oldTask)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-target="#addHours"
									        @if($oldTask->task_status != config('constants.status_active')) disabled @endif>
										<i class="fas fa-business-time svg-small btn-theme-clair-fort"></i>
									</button>
								</div>
							</td>
							<td class="wrap-yes truncate-medium">{{$oldTask->activity_code}}</td>
							<td class="text-center">{{$oldTask->activity_manager_tri}}</td>
							<td class="wrap-yes truncate-large">{{$oldTask->phase_name}}</td>
							<td class="wrap-yes truncate-large style-tache">
								<i class="fas fa-flag @if($oldTask->task_milestone == 1) milestone-yes @else milestone-no @endif"></i>
								{{$oldTask->task_name}}
							</td>
							<td class="wrap-yes truncate-large">{{$oldTask->task_type_name}}</td>
							<td class="text-center wrap-yes truncate-small"
							    data-value="{{ $oldTask->task_start_p }}">{{Carbon\Carbon::parse($oldTask->task_start_p)->format('m/Y')}}</td>
							<td class="text-right style-prevu"
							    data-value="{{$oldTask->task_days_p}}">{{number_format($oldTask->task_days_p, '3', ',', ' ')}}</td>
							<td class="text-right style-realise"
							    data-value="{{$oldTask->task_days_r}}">{{number_format($oldTask->task_days_r, '3', ',', ' ')}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-center table-separator"></div>
		</div>


		{{--TASKS ENTITY--}}
		{{--HISTORIQUE SERVICE/DIRECTION--}}
		@if(auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id') )
			<div class="tab-pane" role="tabpanel" id="tasks_entity">

				<div class="flex-row flex-wrap-no justify-between items-center default-entity-title"
				     role="tablist">
					<div class="flex-row flex-wrap-no justify-between items-center flex-grow-no pad-h-small pad-v-top-medium pad-v-bottom-small">
						<div class="flex-row flex-wrap-no items-center action-btn-group-cra justify-evenly">
							<div class="flex-row flex-wrap-no items-center action-btn-cra justify-evenly">
							</div>
						</div>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-1 pad-h-small pad-v-top-medium pad-v-bottom-small">
						<span class="default-entity-title-data">
							@if(auth()->user()->role_id == config('constants.role_admin_id')
							|| auth()->user()->role_id == config('constants.role_directeur_id'))
								{{session()->get('user_department')}}
							@elseif(auth()->user()->role_id == config('constants.role_service_id'))
								{{session()->get('user_service')}}
							@else{{'-'}}
							@endif
						</span>
						<span class="default-entity-title-label italic">
							@if(auth()->user()->role_id == config('constants.role_admin_id'))
								{{' '}}({{ucfirst(trans('app.admin'))}})@endif
							@if(auth()->user()->role_id == config('constants.role_directeur_id'))
								{{' '}}({{trans('app.department')}})@endif
							@if(auth()->user()->role_id == config('constants.role_service_id'))
								{{' '}}({{trans('app.service')}})@endif
							@if(auth()->user()->role_id == config('constants.role_projet_id'))
								{{' '}}({{'-'}})@endif
							@if(auth()->user()->role_id == config('constants.role_agent_id'))
								{{' '}}({{'-'}})@endif
							@if(auth()->user()->role_id == config('constants.role_prestataire_id'))
								{{' '}}({{'-'}})@endif
						</span>
					</div>
					<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">J.ouvrés <span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span> : </span>
							<span class="default-entity-title-data">{{number_format($entity_open_days_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Absences <span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span> : </span>
							<span class="default-entity-title-data style-absence">{{number_format($entity_total_absence_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Prévu <span
										class="exp-annee">(affiché)</span> : </span>
							<span class="default-entity-title-data style-prevu-total">{{number_format($entity_total_prevu_total_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Réalisé <span
										class="exp-annee">(affiché)</span> : </span>
							<span class="default-entity-title-data style-realise">{{number_format($entity_total_realise, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Réalisé <span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span> : </span>
							<span class="default-entity-title-data style-realise-light no-bold">{{number_format($entity_total_realise_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Restant <span
										class="exp-annee">(affiché)</span> : </span>
							<span class="default-entity-title-data style-prevu">{{number_format($entity_total_restant_month, '3', ',', ' ')}}</span>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table sortable">
						<thead>
						<tr>
							<th class="action-btn-no-header" data-defaultsort="asc">
								{{trans('activity.Name')}}
							</th>
							<th class="text-center tiny-cell">
								{{trans('activity.Manager')}}
							</th>
							<th>
								{{trans('phase.Phase')}}
							</th>
							<th>
								{{trans('task.NameFull')}}
							</th>
							<th>
								{{trans('task.lab_user_affected')}}
							</th>
							<th>
								{{trans('task.Type')}}
							</th>
							<th class="text-center">
								{{'Mois'}}
							</th>
							<th class="text-right tiny-cell">
								{{trans('task.lab_days')}}
							</th>
							<th class="text-right tiny-cell">
								{{trans('task.DaysR')}}
							</th>
							<th class="text-right italic tiny-cell">
								{{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
							</th>
						</tr>
						</thead>
						<tbody id="filter_table">
						@foreach($entityTasks as $entityTask)
							<tr class="action-btn-cell
							@if($entityTask->task_status == config('constants.status_terminated')) tr-task-terminated @endif
							@if($entityTask->task_status == config('constants.status_not_validated')) tr-task-not-validated @endif
							@if($entityTask->task_status == config('constants.status_validated')) tr-task-validated @endif ">
								<td class="wrap-yes truncate-medium action-btn-no-body">{{$entityTask->activity_code}}</td>
								<td class="text-center">{{$entityTask->activity_manager_tri}}</td>
								<td class="wrap-yes truncate-large">{{$entityTask->phase_name}}</td>
								<td class="wrap-yes truncate-large style-tache">
									<i class="fas fa-flag
									@if($entityTask->task_milestone == 1) milestone-yes @else milestone-no @endif"></i>
									{{$entityTask->task_name}} ({{$entityTask->task_status}})
								</td>
								<td class="wrap-yes truncate-large">{{$entityTask->full_name_affected}}</td>
								<td class="wrap-yes truncate-large">{{$entityTask->task_type_name}}</td>
								<td class="text-center wrap-yes truncate-small"
								    data-value="{{ $entityTask->task_start_p }}">{{Carbon\Carbon::parse($entityTask->task_start_p)->format('m/Y')}}</td>
								<td class="text-right style-prevu"
								    data-value="{{$entityTask->task_days_p}}">{{number_format($entityTask->task_days_p, '3', ',', ' ')}}</td>
								<td class="text-right style-realise"
								    data-value="{{$entityTask->task_days_r}}">{{number_format($entityTask->task_days_r, '3', ',', ' ')}}</td>
								<td class="text-right style-realise-light"
								    data-value="{{$entityTask->entity_task_work_days_month}}">{{number_format($entityTask->entity_task_work_days_month, '3', ',', ' ')}}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="text-center table-separator"></div>

			</div>
		@endif

	</div>

	{{--INCLUDE HOURS MODAL--}}
	@include('tasks.task_add_hours')
	@include('tasks.task_modals_index')

@endsection