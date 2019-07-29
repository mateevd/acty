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
			@if(auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id'))
				<li class="pad-h-right-small">
					<a class="flex-row flex-wrap-no nav-link-period" href="#dashboard_entity" role="tab"
					   data-toggle="tab">
						<div>
							@if(auth()->user()->role_id == config('constants.role_admin_id')) {{ucfirst(trans('app.department'))}} @endif
							@if(auth()->user()->role_id == config('constants.role_directeur_id')) {{ucfirst(trans('app.department'))}} @endif
							@if(auth()->user()->role_id == config('constants.role_service_id')) {{ucfirst(trans('app.service'))}} @endif
						</div>
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
	{{--DASHBOARD USER--}}
	<div class="tab-pane" role="tabpanel" id="dashboard_user">
		@include('dashboard.dashboard_agent')
	</div>

	<div class="tab-pane" role="tabpanel" id="dashboard_entity">

		@if(auth()->user()->role_id == config('constants.role_admin_id') ||
			auth()->user()->role_id == config('constants.role_directeur_id') ||
			auth()->user()->role_id == config('constants.role_service_id'))

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
				</div>
			</div>

			{{--DASHBOARD CARDS - START--}}
			<div id="dashboard_entity_card" class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">

				{{--ENTITY ACTIVITIES - START--}}
				<h1 name="resume_card_entity_activities">
					<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
							<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
								<i class="fas fa-tachometer-alt svg-medium style-activite pos-rel-m2"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-small width-100">
								<div>
									{{ucfirst(trans('dashboard.entity_activities'))}}
								</div>
							</div>
						</div>

						<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.running'))}}</label>
										<label for="start_p"
										       class="dashboard-data">{{$entity_activities_running_count}}</label>
									</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
											<label for="start_p"
											       class="dashboard-data style-terminated">{{$entity_activities_terminated_count}}</label>
										</div>
									</div>
							</div>
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-not-validated">{{$entity_activities_validated_count}}</label>
									</div>
								</div>
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-validated">{{$entity_activities_validated_count}}</label>
									</div>
								</div>
							</div>
							@else
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
									<label for="start_p"
									       class="dashboard-data style-validated">{{$entity_activities_validated_count}}</label>
								</div>
							</div>
							@endif
						</div>
					</div>
				</h1>
				{{--ENTITY ACTIVITIES - END--}}

				{{--TASKS FOR ENTITY ACTIVITIES - START--}}
				<h1 name="resume_card_entity_activities_tasks">
					<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
							<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
								<i class="fas fa-tachometer-alt svg-medium style-activite pos-rel-m2"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-small width-100">
								<div>
									{{ucfirst(trans('dashboard.entity_activities_tasks'))}}
								</div>
							</div>
						</div>

						<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.running'))}}</label>
										<label for="start_p"
										       class="dashboard-data">{{$tasks_for_entity_activities_running_count}}</label>
									</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
											<label for="start_p"
											       class="dashboard-data style-terminated">{{$tasks_for_entity_activities_terminated_count}}</label>
										</div>
									</div>
							</div>
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-not-validated">{{$tasks_for_entity_activities_not_validated_count}}</label>
									</div>
								</div>
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-validated">{{$tasks_for_entity_activities_validated_count}}</label>
									</div>
								</div>
							</div>
							@else
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
									<label for="start_p"
									       class="dashboard-data style-validated">{{$tasks_for_entity_activities_validated_count}}</label>
								</div>
							</div>
							@endif
						</div>
					</div>
				</h1>
				{{--TASKS FOR  ACTIVITIES - END--}}

				{{--ENTITY TASKS - START--}}
				<h1 name="resume_card_entity_tasks">
					<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
							<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
								<i class="fas fa-tachometer-alt svg-medium style-tache pos-rel-m2"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-left-small width-100">
								<div>
									{{ucfirst(trans('dashboard.entity_implication'))}}
								</div>
							</div>
						</div>

						<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.planed_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data">{{$entity_tasks_running_count}}</label>
									</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
											<label for="start_p"
											       class="dashboard-data style-terminated">{{$entity_tasks_terminated_count}}</label>
										</div>
									</div>
							</div>
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-not-validated">{{$entity_tasks_not_validated_count}}</label>
									</div>
								</div>
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-validated">{{$entity_tasks_validated_count}}</label>
									</div>
								</div>
							</div>
							@else
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
									<label for="start_p"
									       class="dashboard-data style-validated">{{$entity_tasks_validated_count}}</label>
								</div>
							</div>
							@endif
						</div>
					</div>
				</h1>
				{{--ENTITY TASKS - END--}}

				{{--ACTIVITES FOR ENTITY TASKS - START--}}
				<h1 name="resume_card_entoty_tasks_activities">
					<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
							<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
								<i class="fas fa-tachometer-alt svg-medium style-tache pos-rel-m2"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-left-small width-100">
								<div>
									{{ucfirst(trans('dashboard.entity_implication_activities'))}}
								</div>
							</div>
						</div>

						<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.planed_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data">{{$activities_for_entity_tasks_running_count}}</label>
									</div>
								</div>
								@if (session()->get('cra_validate'))
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
											<label for="start_p"
											       class="dashboard-data style-terminated">{{$activities_for_entity_tasks_terminated_count}}</label>
										</div>
									</div>
							</div>
							<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-not-validated">{{$activities_for_entity_tasks_not_validated_count}}</label>
									</div>
								</div>
								<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
									<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
										<label for="start_p" class="planning-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
										<label for="start_p"
										       class="dashboard-data style-validated">{{$activities_for_entity_tasks_validated_count}}</label>
									</div>
								</div>
							</div>
							@else
						</div>
						<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
							<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
								<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
									<label for="start_p" class="planning-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
									<label for="start_p"
									       class="dashboard-data style-validated">{{$activities_for_entity_tasks_validated_count}}</label>
								</div>
							</div>
							@endif
						</div>
					</div>
				</h1>
				{{--ACTIVITES FOR ENTITY TASKS - END--}}
			</div>

			{{--RESOURCES DASHBOARD - START--}}
			<div class="dashboard-title">
				@if(auth()->user()->role_id == config('constants.role_admin_id')
				|| auth()->user()->role_id == config('constants.role_directeur_id')
				|| auth()->user()->role_id == config('constants.role_service_id'))
					{{ucfirst(trans('dashboard.entity_resources'))}}
				@elseif(auth()->user()->role_id == config('constants.role_projet_id'))
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

			{{--DASHBOARD TIMES CARDS - START--}}
			<div id="dashboard_entity_times_card" class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">

				@for($i=-2; $i<=6; $i++)
					<h1 name="resume_card_entoty_times_m{{$i}}">
						<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

							<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
								<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort style-libelle bold 
									@if(strlen(round($entity_open_days_array[$i],1))>4) font-10px 
									@elseif(strlen(round($entity_open_days_array[$i],1))>3) font-11px 
									@else font-12px 
									@endif marg-h-tiny">{{round($entity_open_days_array[$i],1)}}
								</div>
								<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort style-absence bold 
									@if(strlen(round($entity_abs_array[$i],1))>4) font-10px 
									@elseif(strlen(round($entity_abs_array[$i],1))>3) font-11px 
									@else font-12px 
									@endif">{{round($entity_abs_array[$i],1)}}
								</div>
								<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-small width-100">
									<div>
										{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth($i)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth($i)->year}}
									</div>
								</div>
							</div>


							<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
								<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="card-label">{{ucfirst(trans('app.prevu_total'))}}</label>
											<label for="start_p"
											       class="card-data style-prevu-total">{{round($entity_prevu_total_array[$i],1)}}</label>
										</div>
									</div>
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="card-label">{{ucfirst(trans('app.prevu_restant'))}}</label>
											<label for="start_p"
											       class="card-data style-prevu">{{round($entity_prevu_array[$i],1)}}</label>
										</div>
									</div>
								</div>
								<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="card-label">{{ucfirst(trans('app.realise'))}}</label>
											<label for="start_p"
											       class="card-data style-realise">{{round($entity_realise_array[$i],1)}}</label>
										</div>
									</div>
									<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
										<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
											<label for="start_p" class="card-label">{{ucfirst(trans('app.restant'))}}</label>
											<label for="start_p"
											       class="card-data style-realise-light no-bold">{{round($entity_restant_array[$i],1)}}</label>
										</div>
									</div>
								</div>

							</div>
						</div>
					</h1>
				@endfor
			</div>
			{{--DASHBOARDS TIMES CARDS - END--}}

			<div class="text-center table-separator"></div>
		@endif
	</div>

</div>