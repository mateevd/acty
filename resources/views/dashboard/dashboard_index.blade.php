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
	@include('includes.date_select')
</div>


<div class="tab-content">
	{{--DASHBOARD USER--}}
	<div class="tab-pane" role="tabpanel" id="dashboard_user">
		@include('dashboard.dashboard_agent')
	</div>

	<div class="tab-pane" role="tabpanel" id="dashboard_entity">

		@if(auth()->user()->role_id == config('constants.role_admin_id')|| 
			auth()->user()->role_id == config('constants.role_directeur_id')|| 
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

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
							<div class="flex-row flex-wrap-no justify-center items-center width-rem-3 height-100 border-round bg-clair-fort">
								<i class="far fa-dot-circle svg-huge style-activite"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-center pad-h-small width-100">
								<div class="text-center">
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

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
							<div class="flex-row flex-wrap-no justify-center items-center width-rem-3 height-100 border-round bg-clair-fort">
								<i class="far fa-dot-circle svg-huge style-activite"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-center pad-h-small width-100">
								<div class="text-center">
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

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
							<div class="flex-row flex-wrap-no justify-center items-center width-rem-3 height-100 border-round bg-clair-fort">
								<i class="far fa-dot-circle svg-huge style-tache"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-center pad-h-left-small width-100">
								<div class="text-center">
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

						<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
							<div class="flex-row flex-wrap-no justify-center items-center width-rem-3 height-100 border-round bg-clair-fort">
								<i class="far fa-dot-circle svg-huge style-tache"></i>
							</div>
							<div class="flex-row flex-wrap-yes items-center justify-center pad-h-left-small width-100">
								<div class="text-center">
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

			<div class="dashboard-content justify-evenly pad-h-none">

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
			</div>

			<div class="text-center table-separator"></div>
		@endif
	</div>

</div>