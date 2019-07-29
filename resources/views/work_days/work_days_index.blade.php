@extends('layouts.app')

@section('content')

	{{--SELECT TAB AND PERIOD--}}
	<div class="navbar-tabs">
		<div class="navbar-tabs-select-tab">
			<ul class="nav" role="tablist" id="wdays-tab-selection">
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#times_user" role="tab" data-toggle="tab">
						<div>Mes temps</div>
						<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
							)</span>
					</a>
				</li>
				@if(auth()->user()->role_id == config('constants.role_admin_id') ||
				auth()->user()->role_id == config('constants.role_directeur_id') ||
				auth()->user()->role_id == config('constants.role_service_id'))
					<li class="pad-h-right-small">
						<a class="flex-row flex-wrap-no nav-link-period" href="#times_entity" role="tab"
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

				@if (session()->get('cra_validate') == true && isset($cra_entity) &&
				   (auth()->user()->role_id == config('constants.role_admin_id') ||
					auth()->user()->role_id == config('constants.role_directeur_id') ||
					auth()->user()->role_id == config('constants.role_service_id') )
				)
					<li class="pad-h-right-small">
						<a class="flex-row flex-wrap-no nav-link-period" href="#cra" role="tab" data-toggle="tab">
							<div>CRAs</div>
							<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>
						</a>
					</li>
				@endif
			</ul>
		</div>
		@include('includes.date_select')
	</div>

	<div class="tab-content">
		<div class="tab-pane" role="tabpanel" id="times_user">
			{{--HISTORIQUE UTILISATEUR--}}
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
						{{session()->get('user_first_name')}}{{trans('app.space_separator')}}{{session()->get('user_last_name')}}{{trans('app.space_separator')}}
				</span>
					<span class="default-entity-title-label italic">
					@if(auth()->user()->role_id == config('constants.role_admin_id'))
							({{ucfirst(config('constants.role_admin'))}})@endif
						@if(auth()->user()->role_id == config('constants.role_directeur_id'))
							({{ucfirst(config('constants.role_directeur'))}})@endif
						@if(auth()->user()->role_id == config('constants.role_service_id'))
							({{ucfirst(config('constants.role_service'))}})@endif
						@if(auth()->user()->role_id == config('constants.role_projet')))
						({{ucfirst(config('constants.role_projet'))}})@endif
						@if(auth()->user()->role_id == config('constants.role_agent')))
						({{ucfirst(config('constants.role_agent'))}})@endif
						@if(auth()->user()->role_id == config('constants.role_prestataire')))
						({{ucfirst(config('constants.role_prestataire'))}})@endif
				</span>
				</div>
				<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-15">
						<span class="default-entity-title-label">J.ouvrés{{trans('app.space_separator')}}<span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>{{trans('app.double_dot_separator')}}</span>
						<span class="default-entity-title-data">{{number_format($open_days_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-15">
						<span class="default-entity-title-label">Absences{{trans('app.space_separator')}}<span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>{{trans('app.double_dot_separator')}}</span>
						<span class="default-entity-title-data style-absence">{{number_format($user_total_absence_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-15">
						<span class="default-entity-title-label">Réalisé{{trans('app.space_separator')}}<span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>{{trans('app.double_dot_separator')}}</span>
						<span class="default-entity-title-data style-realise">{{number_format($user_total_realise_month, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-15">
						<span class="default-entity-title-label">Restant{{trans('app.space_separator')}}<span
									class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
								)</span>{{trans('app.double_dot_separator')}}</span>
						<span class="default-entity-title-data style-realise-light no-bold">{{number_format($user_jours_restants_month, '3', ',', ' ')}}</span>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th></th>
						<th class="action-btn-no-header">{{trans('app.lab_activity')}}</th>
						<th class="text-center">{{trans('app.lab_resp')}}</th>
						<th>{{trans('app.lab_phase')}}</th>
						<th>{{trans('app.lab_task')}}</th>
						<th class="text-center">{{'Mois'}}</th>
						<th class="text-center" data-defaultsort="asc">{{trans('charges.Date')}}</th>
						<th>{{ucfirst(trans('app.description'))}}</th>
						<th class="text-right tiny-cell">
							{{trans('charges.lab_realise')}}
						</th>
					</tr>
					</thead>
					<tbody id="filter_table">
					@foreach($userTimes as $userTime)
						<tr class="
						@if($userTime->work_day_status == config('constants.status_terminated')) tr-task-terminated @endif
						@if($userTime->work_day_status == config('constants.status_not_validated')) tr-task-not-validated @endif
						@if($userTime->work_day_status == config('constants.status_validated')) tr-task-validated @endif ">
							<td class="action-btn-item">
								<div class="action-btn-group justify-evenly">
									{{--EDIT TIME--}}
									<button class="btn-common " id="editTimeButton"
									        title="{{ucfirst(trans('app.Edit'))}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-work_day=" {{json_encode($userTime)}} "
									        data-target="#editTime"
									        @if (session()->get('cra_validate') == true &&
												$userTime->work_day_status == config('constants.status_validated'))
									        disabled @endif>
										<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
									</button>

									{{--DELETE TIME--}}
									<button class="btn-common " id="deleteTimeButton"
									        title="{{trans('app.delete')}}"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-placement="bottom"
									        data-work_day_id=" {{json_encode($userTime->work_day_id)}} "
									        data-task_id=" {{json_encode($userTime->task_id)}} "
									        data-phase_id=" {{json_encode($userTime->phase_id)}} "
									        data-activity_id=" {{json_encode($userTime->activity_id)}} "
									        data-target="#deleteTime"
									        @if (session()->get('cra_validate') == true &&
												$userTime->work_day_status == config('constants.status_validated'))
									        disabled @endif>
										<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
									</button>
								</div>
							</td>
							<td class="wrap-yes truncate-medium">{{$userTime->activity_code}}</td>
							<td class="text-center">{{$userTime->activity_manager}}</td>
							<td class="wrap-yes truncate-medium">{{$userTime->phase_name}}</td>
							<td class="wrap-yes truncate-medium style-tache">{{$userTime->task_name}}</td>
							<td class="text-center wrap-yes truncate-small"
							    data-value="{{ $userTime->task_start_p }}">{{Carbon\Carbon::parse($userTime->task_start_p)->format('m/Y')}}</td>
							<td class="text-center">{{\Carbon\Carbon::parse($userTime->work_day_date)->format('d/m/Y')}}</td>
							<td class="wrap-yes truncate-large">{{$userTime->work_day_description}}
								@if(auth()->user()->role_id == config('constants.role_admin_id'))
									{{trans('app.space_separator')}}(s:{{$userTime->work_day_status}})
								@endif
							</td>
							<td data-value="{{$userTime->work_day_hours}}"
							    class="text-right style-realise">{{ number_format( $userTime->work_day_hours, 3, ',', ' ') }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-center table-separator"></div>
		</div>

		{{--HISTORIQUE SERVICE/DIRECTION--}}
		@if(auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id') )
			<div class="tab-pane" role="tabpanel" id="times_entity">
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
					@else{{trans('app.dash_separator')}}
					@endif
				</span>
						<span class="default-entity-title-label italic">
					@if(auth()->user()->role_id == config('constants.role_admin_id'))
								{{trans('app.space_separator')}}({{ucfirst(trans('app.admin'))}})@endif
							@if(auth()->user()->role_id == config('constants.role_directeur_id'))
								{{trans('app.space_separator')}}({{trans('app.department')}})@endif
							@if(auth()->user()->role_id == config('constants.role_service_id'))
								{{trans('app.space_separator')}}({{trans('app.service')}})@endif
							@if(auth()->user()->role_id == config('constants.role_projet')))
							{{trans('app.space_separator')}}(({{trans('app.dash_separator')}}))@endif
							@if(auth()->user()->role_id == config('constants.role_agent')))
							{{trans('app.space_separator')}}(({{trans('app.dash_separator')}}))@endif
							@if(auth()->user()->role_id == config('constants.role_prestataire')))
							{{trans('app.space_separator')}}({{trans('app.dash_separator')}})@endif
				</span>
					</div>
					<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">J.ouvrés{{trans('app.space_separator')}}<span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>
							<span class="default-entity-title-data">{{number_format($open_days_entity, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Absences{{trans('app.space_separator')}}<span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>
							<span class="default-entity-title-data style-absence">{{number_format($entity_total_absences_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Réalisé{{trans('app.space_separator')}}<span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>
							<span class="default-entity-title-data style-realise">{{number_format($entity_total_realise_month, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">Restant{{trans('app.space_separator')}}<span
										class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>
							<span class="default-entity-title-data style-realise-light no-bold">{{number_format($entity_total_restants_month, '3', ',', ' ')}}</span>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table sortable">
						<thead>
						<tr>
							<th class="action-btn-no-header" data-defaultsort="asc">{{trans('app.user')}}</th>
							<th class="text-center tiny-cell">{{trans('charges.lab_department_name')}}</th>
							<th class="text-center tiny-cell">{{trans('charges.lab_service_name')}}</th>
							<th class="text-center">
								{{trans('work_days.lab_open_days')}}
							</th>
							<th class="text-center tiny-cell">
								{{trans('app.absences')}}
							</th>
							<th class="text-right tiny-cell">
								{{trans('charges.lab_realise')}}
							</th>
							<th class="text-right tiny-cell">
								{{trans('charges.lab_restant')}}
							</th>
						</tr>
						</thead>
						<tbody id="filter_table">
						@foreach($entityTimes as $entityTime)
							<tr class=" @if( ($entityTime->user_id == auth()->user()->id) && (
								auth()->user()->role_id == config('constants.role_admin_id') || 
								auth()->user()->role_id == config('constants.role_directeur_id') || 
								auth()->user()->role_id == config('constants.role_service_id') )) tr-connected-user @endif ">
								<td class="tiny-cell wrap-no action-btn-no-body">{{$entityTime->full_name}}</td>
								<td class="text-center">{{$entityTime->department_name}}</td>
								<td class="text-center">{{$entityTime->service_name}}</td>
								<td class="text-center" data-value="{{$open_days_month}}">
									{{number_format($open_days_month, 3, ',', ' ')}}</td>
								<td class="style-absence text-center" data-value="{{$entityTime->total_abs_user}}">
									{{number_format($entityTime->total_abs_user, 3, ',', ' ')}}</td>
								<td class="style-realise text-right" data-value="{{$entityTime->total_realise_user}}">
									{{number_format($entityTime->total_realise_user, 3, ',', ' ')}}</td>
								<td class="text-right style-realise-light"
								    data-value="{{number_format($entityTime->jours_restants, 3, '.', ',')}}">
									{{number_format($entityTime->jours_restants, 3, ',', ' ')}}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="text-center table-separator"></div>
			</div>
		@endif


		{{--CRAS--}}
		@if (session()->has('cra_validate') && isset($cra_entity) &&
		   (auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id') )
		)
			<div class="tab-pane" role="tabpanel" id="cra">
				<div class="flex-row flex-wrap-no justify-between items-center default-entity-title"
				     role="tablist">
					<div class="flex-row flex-wrap-no justify-between items-center flex-grow-no pad-h-small pad-v-top-medium pad-v-bottom-small default-entity-title-btn">
						<div class="flex-row flex-wrap-no items-center action-btn-group-cra justify-evenly">
							<div class="flex-row flex-wrap-no items-center action-btn-cra justify-evenly">

								{{--VALIDATE ALL WD--}}
								<button class="btn-common" id="validateAllWD-btn"
								        title="{{ucfirst(trans('app.validate'))}} {{trans('app.all_man')}}"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#validateAllWD"
								        data-user_id=" {{json_encode($all_user_id)}} "
								        data-current_month=" {{json_encode($current_month)}} "
								        data-current_year=" {{json_encode($current_year)}} ">
									<i class="fas fa-clock svg-large btn-theme-clair-ultra"></i>
								</button>

								{{--DENY ALL WD--}}
								<button class="btn-common" id="denyAllWD-btn"
								        title="{{ucfirst(trans('app.deny'))}} {{trans('app.all_man')}}"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-target="#denyAllWD"
								        data-user_id=" {{json_encode($all_user_id)}} "
								        data-current_month=" {{json_encode($current_month)}} "
								        data-current_year=" {{json_encode($current_year)}} ">
									<i class="fas fa-times svg-large btn-theme-clair-ultra"></i>
								</button>

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
					@else{{trans('app.dash_separator')}}
					@endif
				</span>
						<span class="default-entity-title-label italic">
					@if(auth()->user()->role_id == config('constants.role_admin_id'))
								{{trans('app.space_separator')}}({{ucfirst(trans('app.admin'))}})@endif
							@if(auth()->user()->role_id == config('constants.role_directeur_id'))
								{{trans('app.space_separator')}}({{trans('app.department')}})@endif
							@if(auth()->user()->role_id == config('constants.role_service_id'))
								{{trans('app.space_separator')}}({{trans('app.service')}})@endif
							@if(auth()->user()->role_id == config('constants.role_projet')))
							{{trans('app.space_separator')}}(({{trans('app.dash_separator')}}))@endif
							@if(auth()->user()->role_id == config('constants.role_agent')))
							{{trans('app.space_separator')}}(({{trans('app.dash_separator')}}))@endif
							@if(auth()->user()->role_id == config('constants.role_prestataire')))
							{{trans('app.space_separator')}}(({{trans('app.dash_separator')}}))@endif
				</span>
					</div>

					<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small overflow-x-auto">
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">{{ucfirst(trans('app.running'))}}{{trans('app.space_separator')}}
								<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>

							<span class="default-entity-title-data">{{number_format($cra_entity->realise_active, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">{{ucfirst(trans('app.terminated_man'))}}{{trans('app.space_separator')}}
								<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>

							<span class="default-entity-title-data tr-task-terminated">{{number_format($cra_entity->realise_terminated, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">{{ucfirst(trans('app.denied_man'))}}{{trans('app.space_separator')}}
								<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>

							<span class="default-entity-title-data tr-task-not-validated">{{number_format($cra_entity->realise_not_validated, '3', ',', ' ')}}</span>
						</div>
						<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
							<span class="default-entity-title-label">{{ucfirst(trans('app.validated_man'))}}{{trans('app.space_separator')}}
								<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
									)</span>{{trans('app.double_dot_separator')}}</span>

							<span class="default-entity-title-data tr-task-validated">{{number_format($cra_entity->realise_validated, '3', ',', ' ')}}</span>
						</div>
					</div>
				</div>

				@if(isset($cra_users))
					<div id="user_card"
					     class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">
						@foreach($cra_users as $cra_user)
							<h1 name="{{$cra_user->full_name}}">
								<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

									<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100">
										<div class="flex-row flex-wrap-yes justify-flex-start items-center">
											<div class="flex-row flex-wrap-no items-center action-btn-group-user-card justify-evenly">
												<div class="flex-row flex-wrap-no items-center action-btn-user-card justify-evenly">

													{{--VALIDATE ALL USER WD--}}
													<button class="btn-common" id="validateUserAllWD-btn"
													        title="{{ucfirst(trans('app.validate'))}} {{trans('app.all_man')}}"
													        data-toggle="modal"
													        data-toggle="tooltip"
													        data-placement="bottom"
													        data-target="#validateUserAllWD"
													        data-user_id=" {{json_encode($cra_user->user_id)}} "
													        data-user_name=" {{json_encode($cra_user->full_name)}} "
													        data-current_month=" {{json_encode($current_month)}} "
													        data-current_year=" {{json_encode($current_year)}} ">
														<i class="fas fa-user-clock svg-medium btn-theme-clair-ultra"></i>
													</button>

													{{--DENY ALL USER WD--}}
													<button class="btn-common" id="denyUserAllWD-btn"
													        title="{{ucfirst(trans('app.deny'))}} {{trans('app.all_man')}}"
													        data-toggle="modal"
													        data-toggle="tooltip"
													        data-placement="bottom"
													        data-target="#denyUserAllWD"
													        data-user_id=" {{json_encode($cra_user->user_id)}} "
													        data-user_name=" {{json_encode($cra_user->full_name)}} "
													        data-current_month=" {{json_encode($current_month)}} "
													        data-current_year=" {{json_encode($current_year)}} ">
														<i class="fas fa-user-times svg-medium btn-theme-clair-ultra"></i>
													</button>
												</div>

											</div>

											<div class="flex-row flex-wrap-yes">
												{{$cra_user->full_name}}
											</div>

										</div>

										<div class="flex-row flex-wrap-yes justify-between user-card-days bg-important-back width-100">
											<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-12">
												<span class="user-card-days-label">{{ucfirst(trans('app.running'))}}{{trans('app.double_dot_separator')}}</span>

												<span class="user-card-days-data">{{number_format($cra_user->realise_active, '3', ',', ' ')}}</span>
											</div>
											<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-12">
												<span class="user-card-days-label">{{ucfirst(trans('app.denied_man'))}}{{trans('app.double_dot_separator')}}</span>

												<span class="user-card-days-data tr-task-not-validated">{{number_format($cra_user->realise_not_validated, '3', ',', ' ')}}</span>
											</div>
											<div class="flex-row flex-wrap-no justify-flex-start pad-h-small width-rem-12">
												<span class="user-card-days-label">{{ucfirst(trans('app.terminated_man'))}}{{trans('app.double_dot_separator')}}</span>

												<span class="user-card-days-data tr-task-terminated">{{number_format($cra_user->realise_terminated, '3', ',', ' ')}}</span>
											</div>
											<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-12">
												<span class="user-card-days-label">{{ucfirst(trans('app.validated_man'))}}{{trans('app.double_dot_separator')}}</span>

												<span class="user-card-days-data tr-task-validated">{{number_format($cra_user->realise_validated, '3', ',', ' ')}}</span>
											</div>
										</div>

										<div class="flex-row flex-wrap-yes justify-evenly items-center user-card-body height-100 width-100">
											<button class="btn-common" id="validateWD-btn"
											        title="Validation sélective"
											        data-placement="bottom"
											        data-toggle="modal"
											        data-toggle="tooltip"
											        data-user_id=" {{json_encode($cra_user->user_id)}} "
											        data-user_name=" {{json_encode($cra_user->full_name)}} "
											        data-current_month=" {{json_encode($current_month)}} "
											        data-current_year=" {{json_encode($current_year)}} "
											        data-target="#validateWD">
												<i class="fas fa-list svg-large btn-theme-libelle"></i>
												<i class="fas fa-clock svg-small btn-validation-sign sign-under"></i>
											</button>

											<button class="btn-common" id="denyWD-btn"
											        title="Refus sélectif"
											        data-placement="bottom"
											        data-toggle="modal"
											        data-toggle="tooltip"
											        data-user_id=" {{json_encode($cra_user->user_id)}} "
											        data-user_name=" {{json_encode($cra_user->full_name)}} "
											        data-current_month=" {{json_encode($current_month)}} "
											        data-current_year=" {{json_encode($current_year)}} "
											        data-target="#denyWD">
												<i class="fas fa-list svg-large btn-theme-libelle"></i>
												<i class="fas fa-times svg-small btn-refus-sign sign-under"></i>
											</button>
										</div>

									</div>
							</h1>
						@endforeach
					</div>
				@endif
				@endif
			</div>
	</div>

	@include('work_days.work_days_modals')

@endsection
