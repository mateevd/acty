@if(session()->has('successMessage'))
	<div id="successMessage" class="alert task-success text-center">
		{{ session()->get('successMessage') }}
	</div>
@endif


<div class="flex-row flex-wrap-no overflow-x-auto justify-flex-start items-center default-entity-title" role="tablist">

	<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-no pad-h-small">
		<div class="phase-title-indicator flex-row justify-content-center">
			@if(isset($phases))
				{{--COLLAPSE ALLBUTTON--}}
				<button id="collapseAllButton"
				        class="btn-common  svg-large btn-accordion-all collapsed-all"
				        aria-expanded="true"
				        aria-controls="collapseAll"
				        data-phases=" {{$all_phase_id}} ">
				</button>
			@endif
		</div>
	</div>

	<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-no default-entity-title-btn">
		<div class="action-btn-group-phase-title justify-evenly">
			<div class="action-btn-phase-title justify-evenly">

				{{--CREATE PHASE--}}
				<button class="btn-common"
				        title="{{trans('phase.tool_create')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-target="#createPhase">
					<i class="fas fa-plus svg-large btn-theme-clair-ultra"></i>
				</button>

				{{--MOVE MULTI TASKS--}}
				<button class="btn-common multi-tasks-select"
				        title="{{trans('task.act_move_multi')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-target="#moveMultiTask">
					<i class="fas fa-file-import svg-large btn-theme-clair-ultra btn-tasks-multi"></i>
				</button>

				{{--COPY MULTI TASKS--}}
				<button class="btn-common multi-tasks-select"
				        title="{{trans('task.act_copy_multi')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-target="#copyMultiTask">
					<i class="fas fa-copy svg-large btn-theme-clair-ultra btn-tasks-multi"></i>
				</button>

				{{--DELETE MULTI TASKS--}}
				<button class="btn-common multi-tasks-select" id="deleteMultiTaskButton"
				        title="{{trans('task.act_delete_multi')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-activity_id=" {{json_encode($activity->activity_id)}} "
				        data-target="#deleteMultiTask">
					<i class="fas fa-trash svg-large btn-theme-clair-ultra btn-tasks-multi"></i>
				</button>

				{{--TERMINATE MULTI TASKS--}}
				<button class="btn-common multi-tasks-select" id="terminateMultiTaskButton"
				        title="{{trans('task.act_terminate_multi')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-activity_id=" {{json_encode($activity->activity_id)}} "
				        data-target="#terminateMultiTask">
					<i class="fas fa-check svg-large btn-theme-clair-ultra btn-tasks-multi"></i>
				</button>

				{{--ACTIVATE MULTI TASKS--}}
				<button class="btn-common multi-tasks-select" id="activateMultiTaskButton"
				        title="{{trans('task.act_activate_multi')}}"
				        data-toggle="modal"
				        data-toggle="tooltip"
				        data-placement="bottom"
				        data-activity_id=" {{json_encode($activity->activity_id)}} "
				        data-target="#activateMultiTask">
					<i class="fas fa-undo svg-large btn-theme-clair-ultra btn-tasks-multi"></i>
				</button>
			</div>
		</div>
	</div>

	<div class="flex-row flex-wrap-yes justify-flex-start items-center ">
		<div class="flex-row flex-wrap-yes justify-flex-start items-center flex-grow-no pad-h-small">
			<span class="phases-title-label">{{trans('app.activity')}}{{trans('app.double_dot_separator')}}</span>
			<span class="flex-wrap-yes phases-title-data">{{$activity->activity_name}}</span>
		</div>
		<div class="flex-row flex-wrap-yes justify-flex-start items-center flex-grow-1 pad-h-small">
			<span class="phases-title-label">{{ucfirst(trans('app.code'))}}{{trans('app.double_dot_separator')}}</span>
			<span class="flex-wrap-yes phases-title-data">{{$activity->activity_code}}</span>
		</div>
	</div>
</div>

@if(isset($phases))
	<div class="user-card-outer-separator"></div>
	@foreach($phases as $phase)
		<div class="accordion accordion-icons" id="accordion_phase{{$phase->phase_id}}" role="tab">
			<div class="phases-card marg-small">
				<div class="flex-row flex-wrap-no justify-between items-center" id="heading{{$phase->phase_id}}">

					<div class="table-responsive">
						<table class="table table-phases">
							<thead>
							<tr>
								<th class="phase-indicator phases-card-header text-center">
									{{--COLLAPSE BUTTON--}}
									<button id="collapseButton{{$phase->phase_id}}"
									        class="btn-common  svg-x-small btn-accordion collapsed"
									        aria-expanded="true"
									        aria-controls="collapse{{$phase->phase_id}}"
									        data-toggle="collapse"
									        data-target="#task_panel{{$phase->phase_id}}">
									</button>
								</th>

								<th class="action-btn-item">
									<div class="action-btn-phase justify-evenly">
										{{--EDIT PHASE--}}
										<button id="editButton"
										        class="btn-common "
										        title="{{ucfirst(trans('app.Edit'))}}"
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-phase=" {{json_encode($phase)}} "
										        data-target="#editPhase"
										        @if($phase->phase_status != config('constants.status_active')) disabled @endif>
											<i class="fas fa-edit svg-small btn-theme-clair-ultra"></i>
										</button>
										{{--MOVE PHASE--}}
										<button id="movePhaseButton"
										        class="btn-common "
										        title="{{ucfirst(trans('app.move'))}}"
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-phase_id=" {{json_encode($phase->phase_id)}} "
										        data-phase_name=" {{json_encode($phase->phase_name)}} "
										        data-activity_id=" {{json_encode($phase->activity_id)}} "
										        data-target="#movePhase">
											<i class="fas fa-file-import svg-small btn-theme-clair-ultra"></i>
										</button>

										{{--DELETE PHASE--}}
										<button id="deletePhaseButton"
										        class="btn-common "
										        title="{{ucfirst(trans('app.delete'))}}"
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-phase_id=" {{json_encode($phase->phase_id)}} "
										        data-phase_name=" {{json_encode($phase->phase_name)}} "
										        data-activity_id=" {{json_encode($phase->activity_id)}} "
										        data-target="#deletePhase">
											<i class="fas fa-trash svg-small btn-theme-clair-ultra"></i>
										</button>

										{{--TERMINATE PHASE--}}
										<button id="terminateButton"
										        class="btn-common "
										        title="{{ucfirst(trans('app.terminate'))}}"
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-phase_id=" {{json_encode($phase->phase_id)}} "
										        data-phase_name=" {{json_encode($phase->phase_name)}} "
										        data-activity_id=" {{json_encode($phase->activity_id)}} "
										        data-target="#terminatePhase"
										        @if($phase->phase_status != config('constants.status_active')) hidden @endif>
											<i class="fas fa-check svg-small btn-theme-clair-ultra"></i>
										</button>

										{{--ACTIVATE PHASE--}}
										<button id="activateButton"
										        class="btn-common "
										        title="{{ucfirst(trans('app.activate'))}}"
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-phase_id=" {{json_encode($phase->phase_id)}} "
										        data-phase_name=" {{json_encode($phase->phase_name)}} "
										        data-activity_id=" {{json_encode($phase->activity_id)}} "
										        data-target="#activatePhase"
										        @if ( session()->get('cra_validate') == false && $phase->phase_status != config('constants.status_validated') ) hidden
										        @endif
										        @if ( session()->get('cra_validate') == true &&
														($phase->phase_status != config('constants.status_terminated') &&
														 $phase->phase_status != config('constants.status_not_validated') &&
														 $phase->phase_status != config('constants.status_validated') ) ) hidden @endif >
											<i class="fas fa-undo svg-small btn-theme-clair-ultra"></i>
										</button>

										{{--CHANGE PRIVACY PHASE--}}
										<button id="privacyPhaseButton"
										        class="btn-common
														@if ($phase->phase_private == 1) private @else public @endif"
										        title="{{ucfirst(trans('phase.act_privacy'))}}"
										        data-phase_id=" {{json_encode($phase->phase_id)}} "
										        data-phase_name=" {{json_encode($phase->phase_name)}} "
										        data-toggle="modal"
										        data-toggle="tooltip"
										        data-placement="bottom"
										        data-target="#privacyPhase">
											@if($phase->phase_private == 1)
												<i class="fas fa-lock svg-small"></i>
											@else
												<i class="fas fa-unlock-alt svg-small btn-theme-clair-ultra"></i>
											@endif
										</button>
									</div>
								</th>

								<th class="wrap-yes phases-card-header">
									<div class="flex-row flex-wrap-yes justify-flex-start items-center pad-h-small width-100
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
									@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
									@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif
											">
										<span class="font-12-px no-bold">{{ucfirst(trans('app.phase'))}}{{trans('app.double_dot_separator')}}</span>
										<div class="flex-row flex-wrap-no justify-flex-start items-center bold italic">
												<span class="exp-status style-libelle">
													{{trans('app.space_separator')}}{{'('}}{{$phase->count_tasks_active}}
													/
												</span>
											@if (session()->get('cra_validate'))
												<span class="exp-status style-terminated-dark">
														{{$phase->count_tasks_terminated}}/
													</span>
												<span class="exp-status style-not-validated">
														{{$phase->count_tasks_not_validated}}/
													</span>
											@endif
											<span class="exp-status style-validated-dark">
													{{$phase->count_tasks_validated}}
												</span>
											<span class="exp-status style-libelle">
													{{')'}}
												</span>
										</div>
									</div>
								</th>

								<th class="wrap-yes phases-card-header">
									<div class="flex-row flex-wrap-yes justify-flex-end">
										<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-15">
												<span class="font-12-px no-bold 
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
												@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
												@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif ">{{trans('activity.StartP')}}{{trans('app.double_dot_separator')}}</span>

											<span class="
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
											@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
											@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif
													bold">{{\Carbon\Carbon::parse($phase->phase_start_p)->format('m/Y')}}</span>
										</div>
										<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-15">
												<span class="font-12-px no-bold 
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
												@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
												@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif ">{{trans('activity.DaysP_total')}}{{trans('app.double_dot_separator')}}</span>
											<span class="
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
											@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
											@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif
													style-prevu-total bold">{{number_format($phase->phase_days_p_total, 3, ',', ' ')}}</span>
										</div>
										<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-15">
												<span class="font-12-px no-bold 
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
												@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
												@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif ">{{trans('activity.DaysRestant')}}{{trans('app.double_dot_separator')}}</span>
											<span class="
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
											@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
											@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif
													style-prevu bold">{{number_format($phase->phase_days_p, 3, ',', ' ')}}</span>
										</div>
										<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-15">
												<span class="font-12-px no-bold 
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
												@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
												@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif ">{{trans('activity.DaysR')}}{{trans('app.double_dot_separator')}}</span>
											<span class="
													@if($phase->phase_status == config('constants.status_terminated')) tr-task-terminated @endif
											@if($phase->phase_status == config('constants.status_not_validated')) tr-task-not-validated @endif
											@if($phase->phase_status == config('constants.status_validated')) tr-task-validated @endif
													style-realise bold">{{number_format($phase->phase_days_r, 3, ',', ' ')}}</span>
										</div>
									</div>
								</th>
							</tr>
							</thead>
						</table>
					</div>

				</div>

				{{--TASKS PANEL--}}
				<div id="task_panel{{$phase->phase_id}}" class="collapse" role="tabpanel"
				     data-parent="#accordion_phase{{$phase->phase_id}}"
				     aria-labelledby="task_panel_heading{{$phase->phase_id}}">
					<div class="phases-card-body marg-h-small">
						<div class="table-responsive">
							<table class="table sortable table-tasks">
								<thead>
								<tr class="">
									<th data-defaultsort="disabled" class="width-rem-3"></th>

									<th data-defaultsort="disabled" class="pad-h-none action-btn-group-task">
										<div class="action-btn-group justify-center">
											{{--CREATE TASKS--}}
											<button id="createTaskBtn"
											        class="btn-common "
											        title="{{ucfirst(trans('task.act_create'))}}"
											        data-toggle="modal"
											        data-toggle="tooltip"
											        data-placement="bottom"
											        data-phase=" {{json_encode(['phase_id' => $phase->phase_id, 'phase_name' => $phase->phase_name, 'activity_id' => $phase->activity_id])}} "
											        name="createTaskOccurence"
											        data-target="#createTaskOccurence"
											        @if($phase->phase_status == config('constants.status_deleted')) disabled @endif>
												<i class="far fa-calendar-plus svg-medium btn-theme-fonce-leger"></i>
											</button>
										</div>
									</th>
									<th class="text-left">{{trans('task.NameFull')}}</th>
									<th class="text-left x-large-cell">{{trans('app.lab_task_type')}}</th>
									<th class="large-cell" data-defaultsort="asc">{{trans('app.lab_start')}}</th>
									<th class="text-left xx-large-cell">{{trans('task.lab_user_affected')}}</th>
									<th class="text-right tiny-cell">{{trans('task.DaysP')}}</th>
									<th class="text-right tiny-cell">{{trans('app.lab_realise')}}</th>
								</tr>
								</thead>
								@include('activities.planning_include_task')
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@endif

{{--INCLUDE MODALS--}}
@include('phases.phase_modals')
@include('tasks.task_modals_plan')
@include('tasks.task_add_hours')
