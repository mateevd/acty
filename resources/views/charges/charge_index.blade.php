@extends('layouts.app')

@section('content')

{{--SELECT PERIOD--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="charges-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#charges_all" role="tab" data-toggle="tab">
					<div>{{ucfirst(session()->get('month_name'))}}</div>
					<div class="exp-annee">{{session()->get('current_year')}}</div>
				</a>
			</li>
		</ul>
	</div>
	<div class="navbar-tabs-select-date">
		<form id="date_change" action="{{route('charges')}}" method="get" name="dateSelect" class="hide-submit">
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

<div class="tab-pane" role="tabpanel" id="charges_all">

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
				@elseif(auth()->user()->role_id == config('constants.role_projet_id'))
					{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}{{' '}}
				@elseif(auth()->user()->role_id == config('constants.role_agent_id'))
					{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}{{' '}}
				@elseif(auth()->user()->role_id == config('constants.role_prestataire_id'))
					{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}{{' '}}
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
					({{ucfirst(config('constants.role_projet'))}})@endif
				@if(auth()->user()->role_id == config('constants.role_agent_id'))
					({{ucfirst(config('constants.role_agent'))}})@endif
				@if(auth()->user()->role_id == config('constants.role_prestataire_id'))
					({{ucfirst(config('constants.role_prestataire'))}})@endif
			</span>	
		</div>
		<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
		</div>
	</div>

	<div class="table-responsive">
		<table class="table sortable">
			<thead>
			<tr>
				<th class="tiny-cell action-btn-no-header" data-defaultsort="asc">{{trans('app.user')}}</th>
				<th class="text-center tiny-cell">{{trans('charges.lab_department_name')}}</th>
				<th class="text-center tiny-cell">{{trans('charges.lab_service_name')}}</th>
				@foreach($displayMonths as $displayMonth)
					<th data-defaultsort='disabled'
					    class="text-center">{{substr($displayMonth, 0, 2).'/'.substr($displayMonth, 5, 2)}}
					</th>
				@endforeach
			</tr>
			</thead>
			<tbody id="filter_table">

			{{--CHARGES TABLE--}}
			@foreach ($entity_users as $current_user)
				<tr class=" @if( ($current_user->user_id == auth()->user()->id) && (
								auth()->user()->role_id == config('constants.role_admin_id') || 
								auth()->user()->role_id == config('constants.role_directeur_id') || 
								auth()->user()->role_id == config('constants.role_service_id') )) tr-connected-user @endif ">
					<td class="wrap-no action-btn-no-body">{{$current_user->full_name}}</td>
					<td class="text-center">{{$current_user->department_name}}</td>
					<td class="text-center">{{$current_user->service_name}}</td>
					@foreach($displayMonths as $displayMonth)
						<td class="text-center">
							@foreach($entity_charges as $entity_charge)
								@if($entity_charge->mm == substr($displayMonth, 0, 2) && $entity_charge->user_id == $current_user->user_id)
									{{--DETAILS ACTIVITY--}}
									<button class="btn-common " id="detailsChargesButton"
									        title="{{trans('app.details')}}"
									        data-placement="bottom"
									        data-toggle="modal"
									        data-toggle="tooltip"
									        data-user_id=" {{json_encode($entity_charge->user_id)}} "
									        data-charge_month=" {{json_encode($entity_charge->mm)}} "
									        data-charge_year=" {{json_encode($entity_charge->yy)}} "
											data-full_name=" {{$entity_charge->full_name}} ({{$entity_charge->display_month.'/'.$entity_charge->display_year}}) "
									        data-target="#detailsCharges">
										<i class=""
										   data-toggle="popover"
										   data-trigger="hover"
										   title="{{$entity_charge->trigramme}} - ({{$entity_charge->display_month.'/'.$entity_charge->display_year}})<br>
										   			<span class='style-important'>Charge totale : {{number_format($entity_charge->charge_totale, 0, ',', ' ')}} %</span>"
										   data-placement="right"
										   data-html="true"
										   data-content="
												Jours ouvrés : {{$entity_charge->open_days}}<br>
												Absences posées : <span class='style-absence'> {{number_format($entity_charge->sum_absences, 3, ',', ' ')}}</span><br>
												Jours réalisés : <span class='style-realise'> {{number_format($entity_charge->sum_realise, 3, ',', ' ')}}</span><br>
												Capacité actuelle : <b>{{number_format($entity_charge->user_capacity, 3, ',', ' ')}}</b><br>
												Charge planifiée restante : <span class='style-prevu'>{{number_format($entity_charge->sum_prevu, 3, ',', ' ')}}</span><br>
												<span class='style-important'>Jours planifiables potentiels : {{$entity_charge->jours_planifiables}}</span>">
											@switch('user details')
												@case($entity_charge->charge_totale < 0)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/exclamation-red.png' ) }}"
												     alt="Attention">
												@break
												@case($entity_charge->charge_totale == 0)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/validated.png' ) }}"
												     alt="0%">
												@break
												@case($entity_charge->charge_totale > 0 && $entity_charge->charge_totale <= 20)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/000-wheel.png' ) }}"
												     alt="20%">
												@break
												@case($entity_charge->charge_totale > 20 &&  $entity_charge->charge_totale <= 40)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/025-wheel.png' ) }}"
												     alt="40%">
												@break
												@case($entity_charge->charge_totale > 40 &&  $entity_charge->charge_totale <= 60)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/050-wheel.png' ) }}"
												     alt="60%">
												@break
												@case($entity_charge->charge_totale > 60 &&  $entity_charge->charge_totale <= 80)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/075-wheel.png' ) }}"
												     alt="80%">
												@break
												@case($entity_charge->charge_totale > 80 &&  $entity_charge->charge_totale <= 100)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/100-wheel.png' ) }}"
												     alt="100%">
												@break
												@case($entity_charge->charge_totale > 100)
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/100-red-bb.png' ) }}"
												     alt="+100%">
												@break
												@default
												<img class="img-responsive svg-x-small pad-none pastille-small"
												     src="{{ URL::to('/logos/icones/interrogation.png' ) }}"
												     alt="N/A">
												@break
											@endswitch
										</i>
									</button>

								@endif
							@endforeach
						</td>
					@endforeach
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<div class="text-center table-separator"></div>
</div>

@include('charges.charges_modals')
@endsection
