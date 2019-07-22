@extends('layouts.app')
@section('content')

{{--SELECT TAB--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="planning-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#planning" role="tab" data-toggle="tab">
					<div>Planning</div>
					@if(isset($activity))
						<span class="exp-annee">({{$activity->activity_code}})</span>
					@else
						<span class="exp-annee">(Activité)</span>
					@endif
				</a>
			</li>
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#resume" role="tab" data-toggle="tab">
					<div>Indicateurs</div>
					@if(isset($activity))
						<span class="exp-annee">({{$activity->activity_code}})</span>
					@else
						<span class="exp-annee">(Activité)</span>
					@endif
				</a>
			</li>
		</ul>
	</div>
	<div class="navbar-tabs-select-date">
		<form id="date_change" action="{{route('planification', $activity->activity_id)}}" method="get" name="dateSelect" class="hide-submit">
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
		{{--PLANNING--}}
		<div class="tab-pane" role="tabpanel" id="planning">
			@if(isset($activity))
				@include('activities.planning_include_phase')
			@else
				<div>Erreur</div>
			@endif
		</div>

		<div class="tab-pane" role="tabpanel" id="resume">
		{{--RESUMÉ--}}
		<div class="flex-row flex-wrap-no justify-between items-center default-entity-title" role="tablist">
			<div class="flex-row flex-wrap-no justify-between items-center flex-grow-no pad-h-small pad-v-top-medium pad-v-bottom-small">
				<div class="flex-row flex-wrap-no items-center action-btn-group-cra justify-evenly">
					<div class="flex-row flex-wrap-no items-center action-btn-cra justify-evenly">
					</div>
				</div>
			</div>
			<div class="flex-row flex-wrap-no justify-flex-start items-center flex-grow-1 pad-h-small pad-v-top-medium pad-v-bottom-small">
				<span class="default-entity-title-label">
					{{trans('app.activity')}}{{' : '}}
				</span>

				<span class="default-entity-title-data">
					{{$activity->activity_name}}
				</span>
				<span class="exp-annee default-entity-title-label">
					{{' ('}}
				</span>
				<span class="exp-annee bold italic">
					{{$activity->activity_code}}
				</span>
				<span class="exp-annee default-entity-title-label">
					{{') '}}
				</span>
			</div>				
			<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
			</div>
		</div>

		@if(isset($activity))
			<div id="resume_card" class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">
				{{--DATES--}}
				<h1 name="resume_card_dates">
				<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">
					
					<div class="flex-row flex-wrap-yes justify-center items-center user-card-title user-card-inner-separator width-100 height-rem-3 default-entity-title-data">
						<div class="flex-row flex-wrap-no items-center action-btn-group-user-card justify-evenly">
							<div class="flex-row flex-wrap-no items-center action-btn-user-card justify-evenly"></div>
						</div>
						<div class="flex-row flex-wrap-yes justify-flex-end">Dates</div>									
					</div>

					<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_p" class="planning-label">Début</label>
									<label for="start_p"
									       class="planning-data">{{ \Carbon\Carbon::parse($activity->activity_start_p)->format('d/m/Y') }}</label>
								</div>
							</div>


							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="end_p" class="planning-label">Échéance</label>
									<label for="end_p"
									       class="planning-data">{{ \Carbon\Carbon::parse($activity->activity_end_p)->format('d/m/Y') }}</label>
								</div>
							</div>
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
						
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_r" class="planning-label">Début (réalisé)</label>
									<label for="start_r"
									       class="planning-data">{{ \Carbon\Carbon::parse($activity->activity_start_r)->format('d/m/Y') }}</label>
								</div>
							</div>

							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="end_r" class="planning-label">Fin (réalisé)</label>
									<label for="end_r"
									       class="planning-data">{{ \Carbon\Carbon::parse($activity->activity_end_r)->format('d/m/Y') }}</label>
								</div>
							</div>
						</div>
					</div>

				</div>
				</h1>

				{{--TEMPS--}}
				<h1 name="resume_card_temps">
				<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

					<div class="flex-row flex-wrap-yes justify-center items-center user-card-title user-card-inner-separator width-100 height-rem-3 default-entity-title-data">
						<div class="flex-row flex-wrap-no items-center action-btn-group-user-card justify-evenly">
							<div class="flex-row flex-wrap-no items-center action-btn-user-card justify-evenly"></div>
						</div>
						<div class="flex-row flex-wrap-yes justify-flex-end">Temps</div>									
					</div>

					<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12 width-100">
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="days_p_total" class="planning-label">Total prévu</label>
									<label for="days_p_total"
									       class="planning-data style-prevu-total">{{$activity->activity_days_p_total}}</label>
								</div>
							</div>


							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="days_p" class="planning-label">Restant</label>
									<label for="days_p" class="planning-data style-prevu">{{$activity->activity_days_p}}</label>
								</div>
							</div>
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
						
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="days_r" class="planning-label">Réalisé</label>
									<label for="days_r" class="planning-data style-realise">{{$activity->activity_days_r}}</label>
								</div>
							</div>

							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="enveloppe" class="planning-label">Enveloppe</label>
									<label for="enveloppe" class="planning-data style-enveloppe">{{$activity->activity_enveloppe}}</label>
								</div>
							</div>
						</div>
					</div>

				</div>
				</h1>

				@if(session()->get('budget_option') == 1)
				{{--BUDGET--}}
				<h1 name="resume_card_budget">
				<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">
					
					<div class="flex-row flex-wrap-yes justify-center items-center user-card-title user-card-inner-separator width-100 height-rem-3 default-entity-title-data">
						<div class="flex-row flex-wrap-no items-center action-btn-group-user-card justify-evenly">
							<div class="flex-row flex-wrap-no items-center action-btn-user-card justify-evenly"></div>
						</div>
						<div class="flex-row flex-wrap-yes justify-flex-end">Budget</div>									
					</div>

					<div class="flex-row flex-wrap-yes justify-between user-card-days bg-important-back width-100 height-rem-3">
							<div class="flex-row flex-wrap-no justify-flex-start items-center pad-h-small width-rem-12">
								<span class="planning-label">{{strtoupper(trans('app.capex'))}} : </span>	
								<span class="planning-data style-libelle bold">{{$activity->activity_charges_investment}} €</span>
							</div>
							<div class="flex-row flex-wrap-no justify-flex-end items-center pad-h-small width-rem-12">
								<span class="planning-label">{{strtoupper(trans('app.opex'))}} : </span>
								<span class="planning-data style-libelle bold">{{$activity->activity_charges_operation}} €</span>
							</div>
					</div>

					<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-9 width-100">
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="opex_p_total" class="planning-label">Total prévu (RH)</label>
									<label for="opex_p_total" class="planning-data style-prevu-total">{{$activity->activity_opex_p_total}} €</label>									
								</div>
							</div>


							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="budget_total" class="planning-label">Total</label>
									<label for="budget_total" class="planning-data style-budget-total">{{$activity->activity_budget_total}}
										 €</label>
								</div>
							</div>
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="opex_r" class="planning-label">Consommé (RH)</label>
									<label for="opex_r" class="planning-data style-realise">{{$activity->activity_opex_r}} € </label>
								</div>
							</div>


							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="opex_p" class="planning-label">Restant (RH)</label>
									<label for="opex_p" class="planning-data style-prevu">{{$activity->activity_opex_p}} € </label>
								</div>
							</div>
						</div>
					</div>





				</div>
				</h1>
				@endif

			</div>

		@endif



		</div>
	</div>
	
	<div class="text-center table-separator"></div>
@endsection
