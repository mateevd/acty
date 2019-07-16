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
		<ul class="nav" role="tablist" id="absences-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#absence_user" role="tab" data-toggle="tab">
					<div>Mes absences</div>
					<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
				</a>
			</li>
			@if(auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id'))
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#absences_entity" role="tab"
					   data-toggle="tab">
						<div>
							@if(auth()->user()->role_id == config('constants.role_service_id')) {{ucfirst(trans('app.service'))}} @endif
							@if(auth()->user()->role_id == config('constants.role_directeur_id')) {{ucfirst(trans('app.department'))}} @endif
							@if(auth()->user()->role_id == config('constants.role_admin_id')) {{ucfirst(trans('app.department'))}} @endif
						</div>
						<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
					</a>
				</li>
			@endif		
		</ul>
	</div>
	<div class="navbar-tabs-select-date">
		<form id="date_change" action="{{route('absences')}}" method="get" name="dateSelect" class="hide-submit">
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
	{{--ABSENCE UTILISATEUR--}}
	<div class="tab-pane" role="tabpanel" id="absence_user">
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
					<span class="default-entity-title-label">J.ouvrés <span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span> : </span>
					<span class="default-entity-title-data">{{number_format($open_days_month, '3', ',', ' ')}}</span>
				</div>
				<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
					<span class="default-entity-title-label">Absences <span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span> : </span>
					<span class="default-entity-title-data style-absence">{{number_format($user_total_absence_month, '3', ',', ' ')}}</span>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table sortable">
				<thead>
				<tr>
					<th data-defaultsort="disabled" class="text-center">
						<div class="action-btn-add-absences justify-evenly">
							
							<button class="btn-common " id="addAbsenceButton"
							        title="{{trans('app.add')}}"
							        data-toggle="modal"
							        data-toggle="tooltip"
							        data-placement="bottom"
							        data-target="#addAbsence">
								<i class="fas fa-plus svg-large btn-theme-fonce-leger"></i>
							</button>
						</div>
					</th>
					<th class="text-left">{{trans('absences.lab_type')}}</th>
					<th class="text-center" data-defaultsort="asc">{{trans('absences.lab_absence_date')}}</th>
					<th class="text-left">{{ucfirst(trans('app.description'))}}</th>
					<th class="text-right tiny-cell">
						{{trans('absences.lab_absences')}}
					</th>
				</tr>
				</thead>
				<tbody id="filter_table">
				@foreach($userAbsences as $userAbsence)
					<tr>
						<td class="action-btn-item">
							<div class="action-btn-group justify-evenly">

								{{--EDIT ABSENCE--}}
								<button class="btn-common " id="editAbsenceButton"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-absence=" {{json_encode($userAbsence)}} "
								        title="{{trans('absences.act_update')}}"
								        data-target="#editAbsence">
									<i class="fas fa-edit svg-small btn-theme-clair-fort"></i>
								</button>

								{{--DELETE ABSENCE--}}
								<button class="btn-common " id="deleteAbsenceButton"
								        data-toggle="modal"
								        data-toggle="tooltip"
								        data-placement="bottom"
								        data-absence_id=" {{json_encode($userAbsence->absence_id)}} "
								        title="{{trans('absences.act_delete')}}"
								        data-target="#deleteAbsence">
									<i class="fas fa-trash svg-small btn-theme-clair-fort"></i>
								</button>
							</div>
						</td>
						<td class="text-left">{{$userAbsence->absence_types_name}}</td>
						<td class="text-center">{{\Carbon\Carbon::parse($userAbsence->absence_date)->format('d/m/Y')}}</td>
						<td class="text-left">{{$userAbsence->absence_description}}</td>
						<td data-value="{{$userAbsence->absence_days}}"
						    class="text-right style-absence tiny-cell">{{number_format($userAbsence->absence_days, '3', ',', ' ')}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

		<div class="text-center table-separator"></div>
	</div>

	{{--INCLUDE MODALS CRUD--}}
	@include('absences.absence_modals')
	@include('absences.absence_form')

	{{--ABSENCES SERVICE/DIRECTION--}}
	@if(auth()->user()->role_id == config('constants.role_admin_id') || auth()->user()->role_id == config('constants.role_directeur_id') || auth()->user()->role_id == config('constants.role_service_id'))
		<div class="tab-pane" role="tabpanel" id="absences_entity">
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
						<span class="default-entity-title-label">J.ouvrés <span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span> : </span>
						<span class="default-entity-title-data">{{number_format($open_days_entity, '3', ',', ' ')}}</span>
					</div>
					<div class="flex-row flex-wrap-no justify-flex-end pad-h-small width-rem-20">
						<span class="default-entity-title-label">Absences <span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span> : </span>
						<span class="default-entity-title-data style-absence">{{number_format($entity_total_absences_month, '3', ',', ' ')}}</span>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table sortable">
					<thead>
					<tr>
						<th class="tiny-cell" data-defaultsort="asc">{{trans('app.user')}}</th>
						<th class="text-center tiny-cell">{{trans('app.department')}}</th>
						<th class="text-center tiny-cell">{{trans('app.service')}}</th>
						<th class="">{{trans('absences.lab_type')}}</th>
						<th class="text-right tiny-cell">
							{{trans('absences.lab_absences')}}
						</th>
					</tr>
					</thead>

					{{--TABLE ABSENCES FOR DIR/SERV--}}
					<tbody id="filter_table">
					@foreach($entityAbsences as $entityAbsence)
						<tr class=" @if( ($entityAbsence->user_id == auth()->user()->id) && (
										auth()->user()->role_id == config('constants.role_admin_id') ||
										auth()->user()->role_id == config('constants.role_directeur_id') ||
										auth()->user()->role_id == config('constants.role_service_id') )) tr-connected-user @endif ">
							<td class="wrap-no action-btn-no-body">{{$entityAbsence->full_name}}</td>
							<td class="text-center">{{$entityAbsence->department_name}}</td>
							<td class="text-center">{{$entityAbsence->service_name}}</td>
							<td class="">{{$entityAbsence->absence_types_name}}</td>
							<td class="text-right style-absence" data-value="{{$entityAbsence->total_abs_user_by_type}}">
								{{number_format($entityAbsence->total_abs_user_by_type, '3', ',', ' ')}}
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="text-center table-separator"></div>
		</div>
	@endif
</div>

@endsection





