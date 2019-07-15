@if (session('success'))<div class="session-message session-success">{{ session('success') }}</div>@endif

{{--SELECT PERIOD--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="dashboard-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#dashboard_user" role="tab" data-toggle="tab">
					<div>Mes indicateurs</div>
					<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
						)</span>
				</a>
			</li>
			@if(auth()->user()->roles->name == config('constants.role_admin') ||
			auth()->user()->roles->name == config('constants.role_directeur') ||
			auth()->user()->roles->name == config('constants.role_service'))
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#dashboard_entity" role="tab"
					   data-toggle="tab">
						<div>
							@if(auth()->user()->roles->name == config('constants.role_service')) {{ucfirst(trans('app.service'))}} @endif
							@if(auth()->user()->roles->name == config('constants.role_directeur')) {{ucfirst(trans('app.department'))}} @endif
							@if(auth()->user()->roles->name == config('constants.role_admin')) {{ucfirst(trans('app.department'))}} @endif
						</div>
						<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}}
							)</span>
					</a>
				</li>
			@endif
		</ul>
	</div>
	<div class="navbar-tabs-select-date">
		<form id="date_change" action="{{route('indicateurs')}}" method="get" name="dateSelect" class="hide-submit">
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
	<div class="tab-pane" role="tabpanel" id="dashboard_entity">

		@if(auth()->user()->roles->name == config('constants.role_admin') ||
			auth()->user()->roles->name == config('constants.role_directeur') ||
			auth()->user()->roles->name == config('constants.role_service') ||
			auth()->user()->roles->name == config('constants.role_projet') )

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
						@if(auth()->user()->roles->name == config('constants.role_admin')
						|| auth()->user()->roles->name == config('constants.role_directeur'))
							{{session()->get('user_department')}}
						@elseif(auth()->user()->roles->name == config('constants.role_service'))
							{{session()->get('user_service')}}
						@else{{'-'}}
						@endif
					</span>
					<span class="default-entity-title-label italic">
						@if(auth()->user()->roles->name == config('constants.role_admin'))
							{{' '}}({{ucfirst(trans('app.admin'))}})@endif
						@if(auth()->user()->roles->name == config('constants.role_directeur'))
							{{' '}}({{trans('app.department')}})@endif
						@if(auth()->user()->roles->name == config('constants.role_service'))
							{{' '}}({{trans('app.service')}})@endif
						@if(auth()->user()->roles->name == config('constants.role_projet'))
							{{' '}}({{'-'}})@endif
						@if(auth()->user()->roles->name == config('constants.role_agent'))
							{{' '}}({{'-'}})@endif
						@if(auth()->user()->roles->name == config('constants.role_prestataire'))
							{{' '}}({{'-'}})@endif
					</span>
				</div>
				<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
				</div>
			</div>

			@if(auth()->user()->roles->name == config('constants.role_admin') ||
				auth()->user()->roles->name == config('constants.role_directeur') ||
				auth()->user()->roles->name == config('constants.role_service') )
			<div class="flex-row width-100">
				<div class="flex-row flex-basis-50">
					<div class="flex-col width-100">
						<div class="dashboard-title">
							@if(auth()->user()->roles->name == config('constants.role_admin')
							|| auth()->user()->roles->name == config('constants.role_directeur')
							|| auth()->user()->roles->name == config('constants.role_service'))
								{{ucfirst(trans('dashboard.entity_activities'))}}
							@elseif(auth()->user()->roles->name == config('constants.role_projet'))
								{{ucfirst(trans('dashboard.project_activities'))}}
							@else
								{{ucfirst(trans('dashboard.user_activities'))}}
							@endif
								<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
						</div>

						<div class="dashboard-content justify-evenly pad-h-none">

							{{--ACTIVITIES--}}
							<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
								<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
									<span class="style-activite">{{ucfirst(trans('app.activities'))}}
										@if(auth()->user()->roles->name == config('constants.role_admin')
										|| auth()->user()->roles->name == config('constants.role_directeur'))
											({{session()->get('user_department')}})@endif
										@if(auth()->user()->roles->name == config('constants.role_service'))
											({{session()->get('user_service')}})@endif
										@if(auth()->user()->roles->name == config('constants.role_projet')
										|| auth()->user()->roles->name == config('constants.role_agent'))
											({{session()->get('user_tri')}})@endif
									</span>
								</div>

								<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.running'))}}</div>
									<div class="dashboard-data">{{$entity_activities_running_count}}</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-terminated">{{$entity_activities_terminated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
										<div class="dashboard-data style-not-validated">{{$entity_activities_not_validated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_activities_validated_count}}</div>
									</div>
								@else
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_activities_validated_count}}</div>
									</div>
								@endif
							</div>

							{{--TASKS--}}
							<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
								<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
									<span class="style-tache">{{ucfirst(trans('app.tasks'))}} rattachées</span>
								</div>

								<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.planed_wom_pls'))}}</div>
									<div class="dashboard-data">{{$entity_tasks_running_count}}</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-terminated">{{$entity_tasks_terminated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
										<div class="dashboard-data style-not-validated">{{$entity_tasks_not_validated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_tasks_validated_count}}</div>
									</div>
								@else
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_tasks_validated_count}}</div>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="flex-row flex-basis-50">
					<div class="flex-col width-100">
						<div class="dashboard-title">
							@if(auth()->user()->roles->name == config('constants.role_admin')
							|| auth()->user()->roles->name == config('constants.role_directeur')
							|| auth()->user()->roles->name == config('constants.role_service'))
								{{ucfirst(trans('dashboard.entity_implication'))}}
							@elseif(auth()->user()->roles->name == config('constants.role_projet'))
								{{ucfirst(trans('dashboard.project_implication'))}}
							@else
								{{ucfirst(trans('dashboard.project_implication'))}}
							@endif
							<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
						</div>

						<div class="dashboard-content justify-evenly pad-h-none">

							{{--TASKS--}}
							<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
								<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
									<span class="style-tache">{{ucfirst(trans('app.tasks'))}}
										@if(auth()->user()->roles->name == config('constants.role_admin')
										|| auth()->user()->roles->name == config('constants.role_directeur'))
											({{session()->get('user_department')}})@endif
										@if(auth()->user()->roles->name == config('constants.role_service'))
											({{session()->get('user_service')}})@endif
										@if(auth()->user()->roles->name == config('constants.role_projet')
										|| auth()->user()->roles->name == config('constants.role_agent'))
											({{session()->get('user_tri')}})@endif
									</span>
								</div>

								<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.planed_wom_pls'))}}</div>
									<div class="dashboard-data">{{$entity_tasks_for_entity_running_count}}</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-terminated">{{$entity_tasks_for_entity_terminated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
										<div class="dashboard-data style-not-validated">{{$entity_tasks_for_entity_not_validated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_tasks_for_entity_validated_count}}</div>
									</div>
								@else
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_tasks_for_entity_validated_count}}</div>
									</div>
								@endif
							</div>

							{{--ACTIVITIES--}}
							<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
								<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
									<span class="style-activite">{{ucfirst(trans('app.activities'))}} liées</span>
								</div>

								<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.running'))}}</div>
									<div class="dashboard-data">{{$entity_activities_for_entity_running_count}}</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-terminated">{{$entity_activities_for_entity_terminated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
										<div class="dashboard-data style-not-validated">{{$entity_activities_for_entity_not_validated_count}}</div>
									</div>
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_activities_for_entity_validated_count}}</div>
									</div>
								@else
									<div class="dashboard-card pad-v-small">
											<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
										<div class="dashboard-data style-validated">{{$entity_activities_for_entity_validated_count}}</div>
									</div>
								@endif
							</div>


						</div>
					</div>
				</div>
			</div>
			@endif

			<div class="dashboard-title">
							@if(auth()->user()->roles->name == config('constants.role_admin')
							|| auth()->user()->roles->name == config('constants.role_directeur')
							|| auth()->user()->roles->name == config('constants.role_service'))
								{{ucfirst(trans('dashboard.entity_resources'))}}
							@elseif(auth()->user()->roles->name == config('constants.role_projet'))
								{{ucfirst(trans('dashboard.project_resources'))}}
							@else
								{{ucfirst(trans('dashboard.user_resources'))}}
							@endif
							<span class="exp-annee">
								({{substr(\Carbon\Carbon::parse($current_date)->addMonth(-2)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->addMonth(-2)->format('m/Y'), 5, 2)}}
								<i class="fas fa-arrow-right svg-tiny btn-theme-libelle"></i>
								{{substr(\Carbon\Carbon::parse($current_date)->addMonth(+3)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->addMonth(+3)->format('m/Y'), 5, 2)}})
							</span>
			</div>

			<div class="dashboard-content justify-evenly pad-h-none">

				@if(auth()->user()->roles->name == config('constants.role_admin') ||
					auth()->user()->roles->name == config('constants.role_directeur') ||
					auth()->user()->roles->name == config('constants.role_service') )
				{{--OPEN_DAYS--}}

				<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
					<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small ">J.ouvrés</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[-2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[-1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small tr-connected-user">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[0],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data ">{{round($entity_open_days_array[3],1)}}</div>
					</div>
				</div>


				{{--ABSENCES--}}
				<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
					<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-absence">Absences</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[-2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[-1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small tr-connected-user">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[0],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-absence">{{round($entity_abs_array[3],1)}}</div>
					</div>
				</div>
				@endif

				{{--PREVU-TOTAL--}}
				<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
					<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-prevu-total">Prévu total</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[-2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[-1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small tr-connected-user">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[0],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-prevu-total">{{round($entity_prevu_total_array[3],1)}}</div>
					</div>
				</div>

				{{--REALISE--}}
				<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
					<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-realise">Réalisé</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[-2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[-1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small tr-connected-user">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[0],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise">{{round($entity_realise_array[3],1)}}</div>
					</div>
				</div>

				@if(auth()->user()->roles->name == config('constants.role_admin') ||
					auth()->user()->roles->name == config('constants.role_directeur') ||
					auth()->user()->roles->name == config('constants.role_service') )
				{{--RESTANT--}}
				<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
					<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-realise-light no-bold">Restant</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[-2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[-1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small tr-connected-user">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[0],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[1],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[2],1)}}</div>
					</div>
					<div class="dashboard-card pad-v-small">
						<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
						<div class="dashboard-data style-realise-light no-bold">{{round($entity_restant_array[3],1)}}</div>
					</div>
				</div>	
				@endif	
			</div>

		<div class="text-center table-separator"></div>
		@endif
	</div>
	
	{{--ENTITE--}}
	<div class="tab-pane" role="tabpanel" id="dashboard_user">
		@include('dashboard.dashboard_agent')
	</div>
</div>