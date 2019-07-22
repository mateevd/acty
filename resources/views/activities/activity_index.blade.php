@extends('layouts.app')
@section('content')

	@if(session()->has('flashy_notification.message'))
		<script id="flashy-template" type="text/template">
			<div class="flashy flashy--{{ session()->get('flashy_notification.type') }}">
				<i class="flashy__body"></i>
			</div>
		</script>
	@endif

	{{--SELECT TAB STATUS--}}
	<div class="navbar-tabs">
		<div class="navbar-tabs-select-tab">
			<ul class="nav" role="tablist" id="activities-tab-selection">
				@if(auth()->user()->role_id == config('constants.role_admin_id')
								|| auth()->user()->role_id == config('constants.role_directeur_id')
								|| auth()->user()->role_id == config('constants.role_service_id')
								|| auth()->user()->role_id == config('constants.role_projet_id')
								)
					<li class="pad-h-right-small">
						<a class="flex-row flex-wrap-no nav-link-period" href="#mine" role="tab" data-toggle="tab">
							<div>{{'Autorisées'}}</div>

							<span class="exp-status style-libelle bold italic opacity-9">
							{{trans('app.space_separator')}}{{'('}}{{$activities_mine_count_active}}/
						</span>
							@if (session()->get('cra_validate'))
								<span class="exp-status style-terminated-dark bold italic opacity-9"> 
								{{$activities_mine_count_terminated}}/
							</span>
								<span class="exp-status style-not-validated bold italic opacity-9">
								{{$activities_mine_count_not_validated}}/
							</span>
							@endif
							<span class="exp-status style-validated-dark bold italic opacity-9">
							{{$activities_mine_count_validated}}
						</span>
							<span class="exp-status style-libelle bold italic opacity-9">
							{{')'}}
						</span>
						</a>
					</li>
				@endif
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#all" role="tab" data-toggle="tab">
						<div>{{ucfirst(trans('app.all_wom_pls'))}}</div>

						<span class="exp-status style-libelle bold italic opacity-9">
						{{trans('app.space_separator')}}{{'('}}{{$activities_count_active}}/
					</span>
						@if (session()->get('cra_validate'))
							<span class="exp-status style-terminated-dark bold italic opacity-9"> 
							{{$activities_count_terminated}}/
						</span>
							<span class="exp-status style-not-validated bold italic opacity-9">
							{{$activities_count_not_validated}}/
						</span>
						@endif
						<span class="exp-status style-validated-dark bold italic opacity-9">
						{{$activities_count_validated}}
					</span>
						<span class="exp-status style-libelle bold italic opacity-9">
						{{')'}}
					</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="navbar-tabs-select-date">
			<form id="date_change" action="{{route('activités')}}" method="get" name="dateSelect" class="hide-submit">
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
		{{--MINE TAB--}}
		<div class="tab-pane" role="tabpanel" id="mine">
			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th data-defaultsort="disabled" class="text-center">
							<div class="action-btn-activity justify-center">
								@if(auth()->user()->role_id == config('constants.role_admin_id')
								|| auth()->user()->role_id == config('constants.role_directeur_id')
								|| auth()->user()->role_id == config('constants.role_service_id'))
									{{--CREATE ACTIVITY--}}
									<button class="btn-common " id="createActivityButton"
									        title="{{trans('app.add')}}"
									        data-toggle="modal"
									        data-target="#createActivity">
										<i class="fas fa-plus svg-large btn-theme-fonce-leger"></i>
									</button>
								@endif
								<button class="btn-common " id="downloadButton"
								        title="{{trans('app.export')}}"
								        data-toggle="modal"
								        data-target="#exportTables">
									<i class="fas fa-upload svg-large btn-theme-fonce-leger"></i>
								</button>
							</div>
						</th>
						<th data-defaultsort="asc">{{trans('activity.Name')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.Manager')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.Service')}}</th>
						<th>Code</th>
						<th>Type</th>
						<th class="text-center tiny-cell">{{trans('activity.State')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.StartP')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.EndP')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysP_total_index')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysRestant')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysR')}}</th>
					</tr>
					</thead>

					<tbody id="filter_table">
					@foreach ($activities_mine as $activity)
						<tr class=" @if( auth()->user()->role_id == config('constants.role_admin_id') || 
								(auth()->user()->role_id == config('constants.role_directeur_id') && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id') && auth()->user()->service_id == $activity->activity_service_id) ||
								(auth()->user()->id == $activity->activity_manager) ||
								(auth()->user()->id == $activity->activity_deputy) ) possible-style-mod
								@else tr-activity-not-allowed @endif

						@if($activity->activity_status == config('constants.status_terminated')) tr-task-terminated @endif
						@if($activity->activity_status == config('constants.status_not_validated')) tr-task-not-validated @endif
						@if($activity->activity_status == config('constants.status_validated')) tr-task-validated @endif

								">
							<td class="action-btn-item">
								<div class="action-btn-group justify-evenly opacity-10">
									{{--EDIT ACTIVITY--}}
									<button class="btn-common " id="updateActivityButton"
									        title="{{ucfirst(trans('app.Edit'))}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity=" {{json_encode($activity)}} "
									        @if (( auth()->user()->role_id == config('constants.role_admin_id') ||
													(auth()->user()->role_id == config('constants.role_directeur_id') && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id') && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													$activity->activity_status == config('constants.status_active')) enabled
									        @else disabled @endif
									        data-target="#updateActivity">
										<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
									</button>

									{{--PLAN ACTIVITY--}}
									@if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active')))
										<a id="editActivityButton"
										   title="{{trans('app.plan')}}"
										   data-toggle="tooltip"
										   data-placement="bottom"
										   data-activity=" {{json_encode($activity->activity_id)}} "
										   href="{{url('activities/plan/'.$activity->activity_id)}}">
											<i class="far btn-common  fa-calendar-alt svg-small btn-theme-clair-fort"></i>
										</a>
									@else
										<button class="btn-common " id="01_filler"
										        title="{{trans('app.plan')}} adidi"
										        data-toggle="tooltip"
										        data-placement="bottom" disabled>
											<i class="far fa-calendar-alt svg-small btn-theme-clair-fort"></i>
										</button>
									@endif

									{{--DELETE ACTIVITY--}}
									<button class="btn-common " id="deleteActivityButton"
									        title="{{trans('app.delete')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        @if(auth()->user()->role_id == config('constants.role_admin_id') ||
												(auth()->user()->role_id == config('constants.role_directeur_id') && auth()->user()->department_id == $activity->activity_department_id) ||
												(auth()->user()->role_id == config('constants.role_service_id') && auth()->user()->service_id == $activity->activity_service_id)) enabled
									        @else disabled @endif
									        data-target="#deleteActivity">
										<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
									</button>

									{{--TERMINATE ACTIVITY--}}
									<button class="btn-common " id="terminateActivityButton"
									        title="{{trans('app.terminate')}}"
									        data-placement="bottom"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active'))) enabled
									        @else disabled @endif
									        @if($activity->activity_status != config('constants.status_active')) hidden
									        @endif
									        data-target="#terminateActivity">
										<i class="fas fa-check svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ACTIVATE ACTIVITY--}}
									<button class="btn-common " id="activateActivityButton"
									        title="{{trans('app.activate')}}"
									        data-placement="bottom"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status != config('constants.status_active'))) enabled
									        @else disabled @endif
									        @if($activity->activity_status == config('constants.status_active')) hidden
									        @endif
									        data-target="#activateActivity">
										<i class="fas fa-undo svg-small btn-theme-clair-fort"></i>
									</button>

									{{--DETAILS ACTIVITY--}}
									<button class="btn-common " id="detailsActivityButton"
									        title="{{trans('app.details')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity_id="{{$activity->activity_id}}"
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        data-target="#detailsActivity">
										<i class="fas fa-eye svg-small btn-theme-clair-fort"></i>
									</button>

									{{--INFOS ACTIVITY--}}
									<button class="btn-common "
									        title="{{trans('app.infos')}} (ID:{{$activity->activity_id}})"
									        data-toggle="popover"
									        data-trigger="hover"
									        data-placement="right"
									        data-html="true"
									        data-content="
									<b>Resp. adjoint</b> : {{$activity->activity_deputy_tri ? $activity->activity_deputy_tri : 'n/a'}} - <b>Priorité</b> : {{$activity->activity_priority_name}}
									        @if($activity->activity_priority_id == 1)
											        <i class='fa fa-circle' style='color: black'></i>
@elseif($activity->activity_priority_id == 2)
											        <i class='fa fa-circle' style='color: red'></i>
@elseif($activity->activity_priority_id == 3)
											        <i class='fa fa-circle' style='color: orange'></i>
@elseif($activity->activity_priority_id == 4)
											        <i class='fa fa-circle'style='color: green'></i>
@elseif($activity->activity_priority_id == 5)
											        <i class='fa fa-circle' style='color: white'></i>
@else
											        n/a
@endif<hr>
									<b>Direction métier</b> : {{$activity->activity_business_name}} - <b>Enveloppe</b> : <span class='style-enveloppe'> {{number_format($activity->activity_enveloppe, 3, ',', ' ')}}</span><hr>
									<b>Début réalisé</b> : {{$activity->activity_start_r ? \Carbon\Carbon::parse($activity->activity_start_r)->format('d/m/Y') : 'n/a'}} - <b>Fin réalisé</b> : {{$activity->activity_end_r ? \Carbon\Carbon::parse($activity->activity_end_r)->format('d/m/Y') : 'n/a'}}<br><b>Date demande</b> : {{$activity->activity_date_requested ? \Carbon\Carbon::parse($activity->activity_date_requested)->format('d/m/Y') : 'n/a'}} - <b>Date butoir</b> : {{$activity->activity_date_limit ? \Carbon\Carbon::parse($activity->activity_date_limit)->format('d/m/Y') : 'n/a'}}
									        @if(session()->get('budget_option') == 1)<hr>
										<b>{{strtoupper(trans('app.capex'))}}</b> : {{number_format($activity->activity_charges_investment, 0, ' ', ' ')}} € - 
										<b>{{strtoupper(trans('app.opex'))}}</b> : {{number_format($activity->activity_charges_operation, 0, ' ', ' ')}} €<br>
										<b>Budget RH</b> : {{number_format($activity->activity_opex_p_total , 0, ' ', ' ')}} € - 
										<b>Budget total</b> : <span class='style-total-budget'>{{number_format($activity->activity_budget_total, 0, ' ', ' ')}} €</span>
									@endif ">
										<i class="fas fa-info-circle svg-small btn-theme-clair-fort"></i>
									</button>

									{{--CHANGE PRIVACY ACTIVITY--}}
									<button id="privacyActivityButton"
									        class="btn-common  @if ($activity->activity_private == 1) private @else public @endif "
									        title="{{trans('activity.act_privacy')}}"
									        data-activity_id="{{json_encode($activity->activity_id)}}"
									        data-activity_name="{{json_encode($activity->activity_name)}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active'))) enabled
									        @else disabled @endif
									        data-target="#privacyActivity">
										@if($activity->activity_private == 1)
											<i class="fas fa-lock svg-small"></i>
										@else
											<i class="fas fa-unlock-alt svg-small btn-theme-clair-fort"></i>
										@endif
									</button>
								</div>
							</td>
							<td class="truncate-activites bold"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="right"
							    data-html="true"
							    data-content="{{$activity->activity_name}}">
								<i class="fas fa-ban
								@if(auth()->user()->role_id == config('constants.role_admin_id') ||
								(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
								(auth()->user()->id == $activity->activity_manager) ||
								(auth()->user()->id == $activity->activity_deputy) )
										ban-no @else ban-yes @endif" alt="Droit sur le projet lié au rôle"></i>
								<i class="far fa-star
								@if(auth()->user()->role_id == config('constants.role_admin_id') ||
								(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) )
										allowed-yes @else allowed-no @endif" alt="Droit sur le projet lié au rôle"></i>
								<i class="fas fa-star-half-alt
							@if(auth()->user()->id == $activity->activity_deputy) allowed-yes @else allowed-no @endif"
								   alt="Responsable adjoint du projet"></i>
								<i class="fas fa-star
							@if(auth()->user()->id == $activity->activity_manager) allowed-yes @else allowed-no @endif"
								   alt="Responsable du projet"></i>

								{{$activity->activity_name}} (s:{{$activity->activity_status}})
							</td>
							<td class="wrap-no text-center">{{$activity->activity_manager_tri}}</td>
							<td class="wrap-no text-center">{{$activity->activity_service_code}}</td>
							<td class="truncate-activites"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="left"
							    data-html="true"
							    data-content="{{$activity->activity_code}}">
								{{$activity->activity_code}}</td>
							<td class="truncate-activites"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="left"
							    data-html="true"
							    data-content="{{$activity->activity_type_name}}">
								{{$activity->activity_type_name}}</td>

							<td data-value="{{$activity->activity_state_rank}}" class="wrap-no text-center">
								@if($activity->activity_state_id == config('constants.state_framing'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_cadrage.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Cadrage">
								@elseif($activity->activity_state_id == config('constants.state_planned'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_planifie.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Planifié">
								@elseif($activity->activity_state_id == config('constants.state_inProgress'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_encours.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="{{ucfirst(trans('app.running'))}}">
								@elseif($activity->activity_state_id == config('constants.state_suspended'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_suspendu.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Suspendu">
								@elseif($activity->activity_state_id == config('constants.state_canceled'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_abandonne.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Abandonné">
								@elseif($activity->activity_state_id == config('constants.state_ended'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_fini.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Fini">
								@endif
							</td>
							<td class="wrap-no text-center"
							    data-value="{{ $activity->activity_start_p }}">{{$activity->activity_start_p ? Carbon\Carbon::parse($activity->activity_start_p)->format('m/Y') : null}}</td>
							<td class="wrap-no text-center"
							    data-value="{{ $activity->activity_end_p }}">{{$activity->activity_end_p ? Carbon\Carbon::parse($activity->activity_end_p)->format('m/Y') : null}}</td>
							<td class="wrap-no style-prevu-total text-right"
							    data-value="{{ $activity->activity_days_p_total }}">{{number_format( $activity->activity_days_p_total, 3, ',', ' ')}}</td>
							<td class="wrap-no style-prevu text-right"
							    data-value="{{ $activity->activity_days_p }}">{{number_format( $activity->activity_days_p, 3, ',', ' ')}}</td>
							<td class="wrap-no style-realise text-right"
							    data-value="{{ $activity->activity_days_r }}">{{number_format( $activity->activity_days_r, 3, ',', ' ')}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-center table-separator"></div>
		</div>

		{{--ALL TAB--}}
		<div class="tab-pane" role="tabpanel" id="all">
			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th data-defaultsort="disabled" class="text-center">
							<div class="action-btn-activity justify-center">
								@if(auth()->user()->role_id == config('constants.role_admin_id')
								|| auth()->user()->role_id == config('constants.role_directeur_id')
								|| auth()->user()->role_id == config('constants.role_service_id'))
									{{--CREATE ACTIVITY--}}
									<button class="btn-common " id="createActivityButton"
									        title="{{trans('app.add')}}"
									        data-toggle="modal"
									        data-target="#createActivity">
										<i class="fas fa-plus svg-large btn-theme-fonce-leger"></i>
									</button>
								@endif
								<button class="btn-common " id="downloadButton"
								        title="{{trans('app.export')}}"
								        data-toggle="modal"
								        data-target="#exportTables">
									<i class="fas fa-upload svg-large btn-theme-fonce-leger"></i>
								</button>
							</div>
						</th>
						<th data-defaultsort="asc">{{trans('activity.Name')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.Manager')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.Service')}}</th>
						<th>Code</th>
						<th>Type</th>
						<th class="text-center tiny-cell">{{trans('activity.State')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.StartP')}}</th>
						<th class="text-center tiny-cell">{{trans('activity.EndP')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysP_total_index')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysRestant')}}</th>
						<th class="text-right tiny-cell">{{trans('activity.DaysR')}}</th>
					</tr>
					</thead>

					<tbody id="filter_table">
					@foreach ($activities as $activity)
						<tr class=" @if(auth()->user()->role_id == config('constants.role_admin_id') ||
								(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
								(auth()->user()->id == $activity->activity_manager) ||
								(auth()->user()->id == $activity->activity_deputy) ) possible-style-mod
								@else tr-activity-not-allowed @endif

						@if($activity->activity_status == config('constants.status_terminated')) tr-task-terminated @endif
						@if($activity->activity_status == config('constants.status_not_validated')) tr-task-not-validated @endif
						@if($activity->activity_status == config('constants.status_validated')) tr-task-validated @endif

								">
							<td class="action-btn-item">
								<div class="action-btn-group justify-evenly opacity-10">
									{{--EDIT ACTIVITY--}}
									<button class="btn-common " id="updateActivityButton"
									        title="{{ucfirst(trans('app.Edit'))}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity=" {{json_encode($activity)}} "
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active'))) enabled
									        @else disabled @endif
									        data-target="#updateActivity">
										<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
									</button>

									{{--PLAN ACTIVITY--}}
									@if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active')))
										<a id="editActivityButton"
										   title="{{trans('app.plan')}}"
										   data-toggle="tooltip"
										   data-placement="bottom"
										   data-activity=" {{json_encode($activity->activity_id)}} "
										   href="{{url('activities/plan/'.$activity->activity_id)}}">
											<i class="far btn-common  fa-calendar-alt svg-small btn-theme-clair-fort"></i>
										</a>
									@else
										<button class="btn-common " id="01_filler"
										        title="{{trans('app.plan')}} adidi"
										        data-toggle="tooltip"
										        data-placement="bottom" disabled>
											<i class="far fa-calendar-alt svg-small btn-theme-clair-fort"></i>
										</button>
									@endif

									{{--DELETE ACTIVITY--}}
									<button class="btn-common " id="deleteActivityButton"
									        title="{{trans('app.delete')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        @if(auth()->user()->role_id == config('constants.role_admin_id') ||
												(auth()->user()->role_id == config('constants.role_directeur_id') && auth()->user()->department_id == $activity->activity_department_id) ||
												(auth()->user()->role_id == config('constants.role_service_id') && auth()->user()->service_id == $activity->activity_service_id)) enabled
									        @else disabled @endif
									        data-target="#deleteActivity">
										<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
									</button>

									{{--TERMINATE ACTIVITY--}}
									<button class="btn-common " id="terminateActivityButton"
									        title="{{trans('app.terminate')}}"
									        data-placement="bottom"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active'))) enabled
									        @else disabled @endif
									        @if($activity->activity_status != config('constants.status_active')) hidden
									        @endif
									        data-target="#terminateActivity">
										<i class="fas fa-check svg-small btn-theme-clair-fort"></i>
									</button>

									{{--ACTIVATE ACTIVITY--}}
									<button class="btn-common " id="activateActivityButton"
									        title="{{trans('app.activate')}}"
									        data-placement="bottom"
									        data-activity_id=" {{json_encode($activity->activity_id)}} "
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        data-toggle="modal"
									        data-toggle="tooltip"
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status != config('constants.status_active'))) enabled
									        @else disabled @endif
									        @if($activity->activity_status == config('constants.status_active')) hidden
									        @endif
									        data-target="#activateActivity">
										<i class="fas fa-undo svg-small btn-theme-clair-fort"></i>
									</button>

									{{--DETAILS ACTIVITY--}}
									<button class="btn-common " id="detailsActivityButton"
									        title="{{trans('app.details')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-activity_id="{{$activity->activity_id}}"
									        data-activity_name=" {{json_encode($activity->activity_name)}} "
									        data-target="#detailsActivity">
										<i class="fas fa-eye svg-small btn-theme-clair-fort"></i>
									</button>

									{{--INFOS ACTIVITY--}}
									<button class="btn-common "
									        title="{{trans('app.infos')}} (ID:{{$activity->activity_id}})"
									        data-toggle="popover"
									        data-trigger="hover"
									        data-placement="right"
									        data-html="true"
									        data-content="
									<b>Resp. adjoint</b> : {{$activity->activity_deputy_tri ? $activity->activity_deputy_tri : 'n/a'}} - <b>Priorité</b> : {{$activity->activity_priority_name}}
									        @if($activity->activity_priority_id == 1)
											        <i class='fa fa-circle' style='color: black'></i>
@elseif($activity->activity_priority_id == 2)
											        <i class='fa fa-circle' style='color: red'></i>
@elseif($activity->activity_priority_id == 3)
											        <i class='fa fa-circle' style='color: orange'></i>
@elseif($activity->activity_priority_id == 4)
											        <i class='fa fa-circle'style='color: green'></i>
@elseif($activity->activity_priority_id == 5)
											        <i class='fa fa-circle' style='color: white'></i>
@else
											        n/a
@endif<hr>
									<b>Direction métier</b> : {{$activity->activity_business_name}} - <b>Enveloppe</b> : <span class='style-enveloppe'> {{number_format($activity->activity_enveloppe, 3, ',', ' ')}}</span><hr>
									<b>Début réalisé</b> : {{$activity->activity_start_r ? \Carbon\Carbon::parse($activity->activity_start_r)->format('d/m/Y') : 'n/a'}} - <b>Fin réalisé</b> : {{$activity->activity_end_r ? \Carbon\Carbon::parse($activity->activity_end_r)->format('d/m/Y') : 'n/a'}}<br><b>Date demande</b> : {{$activity->activity_date_requested ? \Carbon\Carbon::parse($activity->activity_date_requested)->format('d/m/Y') : 'n/a'}} - <b>Date butoir</b> : {{$activity->activity_date_limit ? \Carbon\Carbon::parse($activity->activity_date_limit)->format('d/m/Y') : 'n/a'}}
									        @if(session()->get('budget_option') == 1)<hr>
										<b>{{strtoupper(trans('app.capex'))}}</b> : {{number_format($activity->activity_charges_investment, 0, ' ', ' ')}} € - 
										<b>{{strtoupper(trans('app.opex'))}}</b> : {{number_format($activity->activity_charges_operation, 0, ' ', ' ')}} €<br>
										<b>Budget RH</b> : {{number_format($activity->activity_opex_p_total , 0, ' ', ' ')}} € - 
										<b>Budget total</b> : <span class='style-total-budget'>{{number_format($activity->activity_budget_total, 0, ' ', ' ')}} €</span>
									@endif ">
										<i class="fas fa-info-circle svg-small btn-theme-clair-fort"></i>
									</button>

									{{--CHANGE PRIVACY ACTIVITY--}}
									<button id="privacyActivityButton"
									        class="btn-common  @if ($activity->activity_private == 1) private @else public @endif "
									        title="{{trans('activity.act_privacy')}}"
									        data-activity_id="{{json_encode($activity->activity_id)}}"
									        data-activity_name="{{json_encode($activity->activity_name)}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        @if (( (auth()->user()->role_id == config('constants.role_admin_id') ) ||
													(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
													(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
													(auth()->user()->id == $activity->activity_manager) ||
													(auth()->user()->id == $activity->activity_deputy) ) &&
													($activity->activity_status == config('constants.status_active'))) enabled
									        @else disabled @endif
									        data-target="#privacyActivity">
										@if($activity->activity_private == 1)
											<i class="fas fa-lock svg-small"></i>
										@else
											<i class="fas fa-unlock-alt svg-small btn-theme-clair-fort"></i>
										@endif
									</button>
								</div>
							</td>
							<td class="truncate-activites bold"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="right"
							    data-html="true"
							    data-content="{{$activity->activity_name}}">
								<i class="fas fa-ban
								@if(auth()->user()->role_id == config('constants.role_admin_id') ||
								(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) ||
								(auth()->user()->id == $activity->activity_manager) ||
								(auth()->user()->id == $activity->activity_deputy) )
										ban-no @else ban-yes @endif" alt="Droit sur le projet lié au rôle"></i>
								<i class="far fa-star
								@if(auth()->user()->role_id == config('constants.role_admin_id') ||
								(auth()->user()->role_id == config('constants.role_directeur_id')  && auth()->user()->department_id == $activity->activity_department_id) ||
								(auth()->user()->role_id == config('constants.role_service_id')  && auth()->user()->service_id == $activity->activity_service_id) )
										allowed-yes @else allowed-no @endif" alt="Droit sur le projet lié au rôle"></i>
								<i class="fas fa-star-half-alt
							@if(auth()->user()->id == $activity->activity_deputy) allowed-yes @else allowed-no @endif"
								   alt="Responsable adjoint du projet"></i>
								<i class="fas fa-star
							@if(auth()->user()->id == $activity->activity_manager) allowed-yes @else allowed-no @endif"
								   alt="Responsable du projet"></i>

								{{$activity->activity_name}} (s:{{$activity->activity_status}})
							</td>
							<td class="wrap-no text-center">{{$activity->activity_manager_tri}}</td>
							<td class="wrap-no text-center">{{$activity->activity_service_code}}</td>
							<td class="truncate-activites"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="left"
							    data-html="true"
							    data-content="{{$activity->activity_code}}">
								{{$activity->activity_code}}</td>
							<td class="truncate-activites"
							    data-toggle="popover"
							    data-trigger="hover"
							    data-placement="left"
							    data-html="true"
							    data-content="{{$activity->activity_type_name}}">
								{{$activity->activity_type_name}}</td>

							<td data-value="{{$activity->activity_state_rank}}" class="wrap-no text-center">
								@if($activity->activity_state_id == config('constants.state_framing'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_cadrage.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Cadrage">
								@elseif($activity->activity_state_id == config('constants.state_planned'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_planifie.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Planifié">
								@elseif($activity->activity_state_id == config('constants.state_inProgress'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_encours.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="{{ucfirst(trans('app.running'))}}">
								@elseif($activity->activity_state_id == config('constants.state_suspended'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_suspendu.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Suspendu">
								@elseif($activity->activity_state_id == config('constants.state_canceled'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_abandonne.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Abandonné">
								@elseif($activity->activity_state_id == config('constants.state_ended'))
									<img class="activity-state"
									     src="{{ URL::to('/logos/icones/state_fini.png' ) }}"
									     data-toggle="popover"
									     data-trigger="hover"
									     data-placement="right"
									     data-html="true"
									     data-content="Fini">
								@endif
							</td>
							<td class="wrap-no text-center"
							    data-value="{{ $activity->activity_start_p }}">{{$activity->activity_start_p ? Carbon\Carbon::parse($activity->activity_start_p)->format('m/Y') : null}}</td>
							<td class="wrap-no text-center"
							    data-value="{{ $activity->activity_end_p }}">{{$activity->activity_end_p ? Carbon\Carbon::parse($activity->activity_end_p)->format('m/Y') : null}}</td>
							<td class="wrap-no style-prevu-total text-right"
							    data-value="{{ $activity->activity_days_p_total }}">{{number_format( $activity->activity_days_p_total, 3, ',', ' ')}}</td>
							<td class="wrap-no style-prevu text-right"
							    data-value="{{ $activity->activity_days_p }}">{{number_format( $activity->activity_days_p, 3, ',', ' ')}}</td>
							<td class="wrap-no style-realise text-right"
							    data-value="{{ $activity->activity_days_r }}">{{number_format( $activity->activity_days_r, 3, ',', ' ')}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-center table-separator"></div>
		</div>

	</div>

	@if(isset($activity))
		@include('activities.activity_modals')
	@endif

@endsection