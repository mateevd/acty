<tbody id="filter_table">
@foreach($tasks as $task)
	@if($task->phase_id == $phase->phase_id)
		<tr class="	@if($task->task_status == config('constants.status_terminated')) tr-task-terminated @endif
		@if($task->task_status == config('constants.status_not_validated')) tr-task-not-validated @endif
		@if($task->task_status == config('constants.status_validated')) tr-task-validated @endif ">
			<td class="pad-h-none">
				<div class="flex-row flex-wrap-yes justify-center align-center width-rem-3">
					<input class="multi-tasks-select-checkboxes" type="checkbox" name="selectTasks[]"
					       value="{{$task->task_id}}">
				</div>
			</td>

			<td class="action-btn-item pad-h-none">
				<div class="action-btn-group justify-evenly">

					{{--EDIT TASK--}}
					<button id="editTaskButton"
					        class="btn-common"
					        title="{{ucfirst(trans('app.Edit'))}}"
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-task=" {{json_encode($task)}} "
					        data-phase_id=" {{json_encode($task->phase_id)}} "
					        data-activity_id=" {{json_encode($activity->activity_id)}} "
					        data-target="#editTask"
					        @if($task->task_status != config('constants.status_active')) disabled @endif>
						<i class="fas fa-edit svg-xx-small btn-theme-clair-fort"></i>
					</button>

					{{--COPY TASK--}}
					<button id="copyTaskButton"
					        class="btn-common"
					        title="{{trans('app.copy')}}"
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-task_id=" {{json_encode($task->task_id)}} "
					        data-task_name=" {{json_encode($task->task_name)}} "
					        data-target="#copySingleTask"
					        @if($task->task_status != config('constants.status_active')) disabled @endif>
						<i class="fas fa-copy svg-xx-small btn-theme-clair-fort"></i>
					</button>

					{{--DELETE TASK--}}
					<button id="deleteTaskButton"
					        class="btn-common"
					        title="{{trans('app.delete')}}"
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-task_id=" {{json_encode($task->task_id)}} "
					        data-task_name=" {{json_encode($task->task_name)}} "
					        data-phase_id=" {{json_encode($task->phase_id)}} "
					        data-activity_id=" {{json_encode($activity->activity_id)}} "
					        data-target="#deleteTask">
						<i class="fas fa-trash svg-xx-small btn-theme-clair-fort"></i>
					</button>

					{{--TERMINATE TASK--}}
					<button id="terminateTaskButton"
					        class="btn-common"
					        title="{{trans('app.terminate')}}"
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-task_id=" {{json_encode($task->task_id)}} "
					        data-task_name=" {{json_encode($task->task_name)}} "
					        data-phase_id=" {{json_encode($task->phase_id)}} "
					        data-activity_id=" {{json_encode($activity->activity_id)}} "
					        data-target="#terminateTask"
					        @if($task->task_status != config('constants.status_active')) hidden @endif>
						<i class="fas fa-check svg-xx-small btn-theme-clair-fort"></i>
					</button>

					{{--ACTIVATE TASK--}}
					<button id="activateTaskButton"
					        class="btn-common"
					        title="{{trans('app.activate')}}"
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-task_id=" {{json_encode($task->task_id)}} "
					        data-task_name=" {{json_encode($task->task_name)}} "
					        data-phase_id=" {{json_encode($task->phase_id)}} "
					        data-activity_id=" {{json_encode($activity->activity_id)}} "
					        data-target="#activateTask"
							@if ( 	$task->task_status != config('constants.status_terminated') &&
									$task->task_status != config('constants.status_not_validated') &&
									$task->task_status != config('constants.status_validated') )
								hidden
								@endif >
						<i class="fas fa-undo svg-xx-small btn-theme-clair-fort"></i>
					</button>

					{{--ADD HOURS--}}
					<button id="addHoursButton"
					        class="btn-common"
					        title="{{trans('work_days.act_create_hours')}}"
					        data-task_show=" {{json_encode($task)}} "
					        data-toggle="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        data-target="#addHours"
					        @if($task->task_status != config('constants.status_active') || 
					        	$task->task_user_id != auth()->user()->id) disabled @endif>
						<i class="fas fa-business-time svg-xx-small btn-theme-clair-fort"></i>
					</button>

				</div>

			</td>

			<td class="text-left wrap-yes truncate-large style-tache">
				<i class="fas fa-flag 
					@if($task->task_milestone == 1) milestone-yes 
					@else milestone-no @endif"></i>
				{{$task->task_name}} ({{$task->task_status}})
			</td>
			<td class="text-left wrap-yes truncate-large">{{$task->task_type_name}}</td>
			<td class="wrap-yes truncate-small"
			    data-value="{{ $task->task_start_p }}">{{$task->task_start_p ? Carbon\Carbon::parse($task->task_start_p)->format('m/Y') : null}}</td>
			<td class="text-left wrap-yes truncate-large">
				@if($task->task_user_id)
					{{--SHOW USER DETAILS FOR MONTH--}}
					<button class="btn-common"
						   title="{{$task->trigramme}} - ({{$task->display_month.'/'.$task->display_year}})<br>
						   			<span class='style-important'>Charge totale : {{number_format($task->charge_totale, 0, ',', ' ')}} %</span>"
					        data-toggle="popover"
					        data-trigger="hover"
					        data-placement="right"
					        data-html="true"
						   data-content="
								Jours ouvrés : {{$task->open_days}}<br>
								Absences posées : <span class='style-absence'> {{number_format($task->sum_absences, 3, ',', ' ')}}</span><br>
								Jours réalisés : <span class='style-realise'> {{number_format($task->sum_realise, 3, ',', ' ')}}</span><br>
								Capacité actuelle : <b>{{number_format($task->user_capacity, 3, ',', ' ')}}</b><br>
								Charge planifiée restante : <span class='style-prevu'>{{number_format($task->sum_prevu, 3, ',', ' ')}}</span><br>
								<span class='style-important'>Jours planifiables potentiels : {{$task->jours_planifiables}}</span>">
							@switch('user details')
								@case($task->charge_totale == 0)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/100-green.png' ) }}"
								     alt="0%">
								@break
								@case($task->charge_totale > 0 && $task->charge_totale <= 20)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/000-wheel.png' ) }}"
								     alt="20%">
								@break
								@case($task->charge_totale > 20 &&  $task->charge_totale <= 40)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/025-wheel.png' ) }}"
								     alt="40%">
								@break
								@case($task->charge_totale > 40 &&  $task->charge_totale <= 60)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/050-wheel.png' ) }}"
								     alt="60%">
								@break
								@case($task->charge_totale > 60 &&  $task->charge_totale <= 80)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/075-wheel.png' ) }}"
								     alt="80%">
								@break
								@case($task->charge_totale > 80 &&  $task->charge_totale <= 100)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/100-wheel.png' ) }}"
								     alt="100%">
								@break
								@case($task->charge_totale > 100)
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/100-wheel-red.png' ) }}"
								     alt="+100%">
								@break
								@default
								<img class="img-responsive svg-small pad-none pastille"
								     src="{{ URL::to('/logos/icones/000-black.png' ) }}"
								     alt="N/A">
								@break
							@endswitch
					</button>
					{{$task->full_name}}
				@else
					<i>Non affectée</i>
				@endif
			</td>
			<td class="text-right style-prevu"
			    data-value="{{ $task->task_days_p }}">{{number_format($task->task_days_p, '3', ',', ' ')}}</td>
			<td class="text-right style-realise"
			    data-value="{{ $task->task_days_r }}">{{number_format($task->task_days_r, '3', ',', ' ')}}</td>
		</tr>
	@endif
@endforeach
</tbody>

