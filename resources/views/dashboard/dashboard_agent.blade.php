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
					{{session()->get('user_first_name')}} {{session()->get('user_last_name')}}
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
	</div>
</div>

{{--DASHBOARD CARDS - START--}}
<div id="dashboard_user_card" class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">

	{{--USER ACTIVITIES - START--}}
	<h1 name="resume_card_user_activities">
		<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

			<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
				<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
					<i class="fas fa-tachometer-alt svg-medium style-activite pos-rel-m2"></i>
				</div>
				<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-small width-100">
					<div>
						{{ucfirst(trans('dashboard.user_activities'))}}
					</div>
				</div>
			</div>

			<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.running'))}}</label>
							<label for="start_p"
							       class="card-data">{{$user_activities_running_count}}</label>
						</div>
					</div>
					@if (session()->get('cra_validate'))
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
								<label for="start_p"
								       class="card-data style-terminated">{{$user_activities_terminated_count}}</label>
							</div>
						</div>
				</div>
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-not-validated">{{$user_activities_not_validated_count}}</label>
						</div>
					</div>
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-validated">{{$user_activities_validated_count}}</label>
						</div>
					</div>
				</div>
				@else
			</div>
			<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
				<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
					<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
						<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
						<label for="start_p"
						       class="card-data style-validated">{{$user_activities_validated_count}}</label>
					</div>
				</div>
				@endif
			</div>
		</div>
	</h1>
	{{--USER ACTIVITIES - END--}}

	{{--TASKS FOR USER ACTIVITIES - START--}}
	<h1 name="resume_card_user_activities_tasks">
		<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

			<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
				<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
					<i class="fas fa-tachometer-alt svg-medium style-activite pos-rel-m2"></i>
				</div>
				<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-left-small width-100">
					<div>
						{{ucfirst(trans('dashboard.user_activities_tasks'))}}
					</div>
				</div>
			</div>

			<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.planed_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data">{{$tasks_for_user_activities_running_count}}</label>
						</div>
					</div>
					@if (session()->get('cra_validate'))
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
								<label for="start_p"
								       class="card-data style-terminated">{{$tasks_for_user_activities_terminated_count}}</label>
							</div>
						</div>
				</div>
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-not-validated">{{$tasks_for_user_activities_validated_count}}</label>
						</div>
					</div>
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-validated">{{$tasks_for_user_activities_validated_count}}</label>
						</div>
					</div>
				</div>
				@else
			</div>
			<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
				<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
					<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
						<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
						<label for="start_p"
						       class="card-data style-validated">{{$user_tasks_validated_count}}</label>
					</div>
				</div>
				@endif
			</div>
		</div>
	</h1>
	{{--TASKS FOR USER ACTIVITIES - END--}}

	{{--USER TASKS - START--}}
	<h1 name="resume_card_user_tasks">
		<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

			<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
				<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
					<i class="fas fa-tachometer-alt svg-medium style-tache pos-rel-m2"></i>
				</div>
				<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-left-small width-100">
					<div>
						{{ucfirst(trans('dashboard.user_implication'))}}
					</div>
				</div>
			</div>

			<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.planed_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data">{{$user_tasks_running_count}}</label>
						</div>
					</div>
					@if (session()->get('cra_validate'))
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
								<label for="start_p"
								       class="card-data style-terminated">{{$user_tasks_terminated_count}}</label>
							</div>
						</div>
				</div>
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-not-validated">{{$user_tasks_not_validated_count}}</label>
						</div>
					</div>
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-validated">{{$user_tasks_validated_count}}</label>
						</div>
					</div>
				</div>
				@else
			</div>
			<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
				<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
					<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
						<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
						<label for="start_p"
						       class="card-data style-validated">{{$user_tasks_validated_count}}</label>
					</div>
				</div>
				@endif
			</div>
		</div>
	</h1>
	{{--USER TASKS - END--}}

	{{--ACTIVITIES FOR USER TASKS - START--}}
	<h1 name="resume_card_user_tasks_activities">
		<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

			<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold pad-h-tiny">
				<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort">
					<i class="fas fa-tachometer-alt svg-medium style-tache pos-rel-m2"></i>
				</div>
				<div class="flex-row flex-wrap-yes items-center justify-flex-start pad-h-left-small width-100">
					<div>
						{{ucfirst(trans('dashboard.user_implication_activities'))}}
					</div>
				</div>
			</div>

			<div class="flex-row flex-wrap-yes justify-evenly project-summary pad-h-medium pad-v-small height-rem-12">
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.planed_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data">{{$activities_for_user_tasks_running_count}}</label>
						</div>
					</div>
					@if (session()->get('cra_validate'))
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
								<label for="start_p"
								       class="card-data style-terminated">{{$activities_for_user_tasks_terminated_count}}</label>
							</div>
						</div>
				</div>
				<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.denied_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-not-validated">{{$activities_for_user_tasks_not_validated_count}}</label>
						</div>
					</div>
					<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
						<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
							<label for="start_p" class="card-label">{{ucfirst(trans('app.validated_wom_pls'))}}</label>
							<label for="start_p"
							       class="card-data style-validated">{{$activities_for_user_tasks_validated_count}}</label>
						</div>
					</div>
				</div>
				@else
			</div>
			<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
				<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
					<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
						<label for="start_p" class="card-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</label>
						<label for="start_p"
						       class="card-data style-validated">{{$activities_for_user_tasks_validated_count}}</label>
					</div>
				</div>
				@endif
			</div>
		</div>
	</h1>
	{{--ACTIVITIES FOR USER TASKS - END--}}

</div>
{{--DASHBOARD CARDS - END--}}

{{--RESOURCES DASHBOARD - START--}}
<div class="dashboard-title">{{ucfirst(trans('dashboard.user_resources'))}}
	<span class="exp-annee">
		({{substr(\Carbon\Carbon::parse($current_date)->addMonth(-2)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->addMonth(-2)->format('m/Y'), 5, 2)}}
		<i class="fas fa-arrow-right svg-tiny btn-theme-libelle"></i>
		{{substr(\Carbon\Carbon::parse($current_date)->addMonth(+3)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->addMonth(+3)->format('m/Y'), 5, 2)}})
	</span>
</div>


{{--DASHBOARD TIMES CARDS - START--}}
<div id="dashboard_user_times_card" class="flex-row flex-wrap-yes justify-flex-start items-center user-card-outer-separator">

	@for($i=-2; $i<=6; $i++)
		<h1 name="resume_card_user_times_m{{$i}}">
			<div class="flex-col flex-wrap-no justify-flex-start items-center width-rem-25 height-rem-15 marg-small user-card">

				<div class="flex-row flex-wrap-no justify-flex-start items-center user-card-title user-card-inner-separator width-100 height-rem-3 font-12px bold">
					<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort style-libelle bold 
							@if(strlen(round($user_open_days_array[$i],1))>4) font-10px 
							@elseif(strlen(round($user_open_days_array[$i],1))>3) font-11px 
							@else font-12px 
							@endif marg-h-tiny">{{round($user_open_days_array[$i],1)}}
					</div>
					<div class="flex-row flex-wrap-no justify-center items-center flex-shrink-no width-rem-3b height-rem-3b border-round bg-clair-fort style-absence bold 
							@if(strlen(round($user_abs_array[$i],1))>4) font-10px 
							@elseif(strlen(round($user_abs_array[$i],1))>3) font-11px 
							@else font-12px 
							@endif">{{round($user_abs_array[$i],1)}}
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
								       class="card-data style-prevu-total">{{round($user_prevu_total_array[$i],1)}}</label>
							</div>
						</div>
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.prevu_restant'))}}</label>
								<label for="start_p"
								       class="card-data style-prevu">{{round($user_prevu_array[$i],1)}}</label>
							</div>
						</div>
					</div>
					<div class="flex-row flex-wrap-no justify-evenly align-stretch width-100">
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.realise'))}}</label>
								<label for="start_p"
								       class="card-data style-realise">{{round($user_realise_array[$i],1)}}</label>
							</div>
						</div>
						<div class="flex-col flex-wrap-no justify-center pad-h-small align-stretch">
							<div class="flex-col flex-wrap-no justify-fled-end text-center pad-v-only-small">
								<label for="start_p" class="card-label">{{ucfirst(trans('app.restant'))}}</label>
								<label for="start_p"
								       class="card-data style-realise-light no-bold">{{round($user_restant_array[$i],1)}}</label>
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
