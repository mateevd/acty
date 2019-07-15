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
					@if(auth()->user()->roles->name == (config('constants.role_admin')))
						({{ucfirst(config('constants.role_admin'))}})@endif
					@if(auth()->user()->roles->name == (config('constants.role_directeur')))
						({{ucfirst(config('constants.role_directeur'))}})@endif
					@if(auth()->user()->roles->name == (config('constants.role_service')))
						({{ucfirst(config('constants.role_service'))}})@endif
					@if(auth()->user()->roles->name == (config('constants.role_projet')))
						({{ucfirst(config('constants.role_projet'))}})@endif
					@if(auth()->user()->roles->name == (config('constants.role_agent')))
						({{ucfirst(config('constants.role_agent'))}})@endif
					@if(auth()->user()->roles->name == (config('constants.role_prestataire')))
						({{ucfirst(config('constants.role_prestataire'))}})@endif
				</span>
		</div>
		<div class="flex-row flex-wrap-yes justify-flex-end pad-h-small pad-v-top-medium pad-v-bottom-small">
		</div>
	</div>

	<div class="flex-row width-100">
		<div class="flex-row flex-basis-50">
			<div class="flex-col width-100">
				<div class="dashboard-title">
						{{ucfirst(trans('dashboard.user_activities'))}}<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
				</div>

				<div class="dashboard-content justify-evenly pad-h-none">

					{{--ACTIVITIES--}}
					<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
						<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
							<span class="style-activite">{{ucfirst(trans('app.activities'))}} ({{session()->get('user_tri')}})</span>
						</div>

						<div class="dashboard-card pad-v-small">
							<div class="dashboard-label">{{ucfirst(trans('app.running'))}}</div>
							<div class="dashboard-data">{{$user_activities_running_count}}</div>
						</div>
						@if (session()->get('cra_validate'))
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-terminated">{{$user_activities_terminated_count}}</div>
							</div>
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
								<div class="dashboard-data style-not-validated">{{$user_activities_not_validated_count}}</div>
							</div>				
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_activities_validated_count}}</div>
							</div>
						@else
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_activities_validated_count}}</div>
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
							<div class="dashboard-data">{{$user_tasks_running_count}}</div>
						</div>
						@if (session()->get('cra_validate'))
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-terminated">{{$user_tasks_terminated_count}}</div>
							</div>
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
								<div class="dashboard-data style-not-validated">{{$user_tasks_not_validated_count}}</div>
							</div>				
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_tasks_validated_count}}</div>
							</div>
						@else
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_tasks_validated_count}}</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="flex-row flex-basis-50">
			<div class="flex-col width-100">
				<div class="dashboard-title">
					{{ucfirst(trans('dashboard.user_implication'))}}<span class="exp-annee">({{substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 0, 2).'/'.substr(\Carbon\Carbon::parse($current_date)->format('m/Y'), 5, 2)}})</span>
				</div>

				<div class="dashboard-content justify-evenly pad-h-none">

					{{--TASKS--}}
					<div class="dashboard-column flex-basis-50 flex-grow-1 flex-shrink-1">
						<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small">
							<span class="style-tache">{{ucfirst(trans('app.tasks'))}} ({{session()->get('user_tri')}})
							</span>
						</div>

						<div class="dashboard-card pad-v-small">
							<div class="dashboard-label">{{ucfirst(trans('app.planed_wom_pls'))}}</div>
							<div class="dashboard-data">{{$user_tasks_for_entity_running_count}}</div>
						</div>
						@if (session()->get('cra_validate'))
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-terminated">{{$user_tasks_for_entity_terminated_count}}</div>
							</div>
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
								<div class="dashboard-data style-not-validated">{{$user_tasks_for_entity_not_validated_count}}</div>
							</div>				
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_tasks_for_entity_validated_count}}</div>
							</div>
						@else
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_tasks_for_entity_validated_count}}</div>
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
							<div class="dashboard-data">{{$user_activities_for_entity_running_count}}</div>
						</div>
						@if (session()->get('cra_validate'))
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-terminated">{{$user_activities_for_entity_terminated_count}}</div>
							</div>
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.denied_wom_pls'))}}</div>
								<div class="dashboard-data style-not-validated">{{$user_activities_for_entity_not_validated_count}}</div>
							</div>				
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.validated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_activities_for_entity_validated_count}}</div>
							</div>
						@else
							<div class="dashboard-card pad-v-small">
									<div class="dashboard-label">{{ucfirst(trans('app.terminated_wom_pls'))}}</div>
								<div class="dashboard-data style-validated">{{$user_activities_for_entity_validated_count}}</div>
							</div>
						@endif
					</div>


				</div>
			</div>
		</div>
	</div>

	<div class="dashboard-title">{{ucfirst(trans('dashboard.user_resources'))}}
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
				<div class="dashboard-data ">{{round($user_open_days_array[-2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data ">{{round($user_open_days_array[-1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small tr-connected-user">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data ">{{round($user_open_days_array[0],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data ">{{round($user_open_days_array[1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data ">{{round($user_open_days_array[2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data ">{{round($user_open_days_array[3],1)}}</div>
			</div>
		</div>


		{{--ABSENCES--}}
		<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
			<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-absence">Absences</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[-2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[-1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small tr-connected-user">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[0],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-absence">{{round($user_abs_array[3],1)}}</div>
			</div>
		</div>

		{{--PREVU-TOTAL--}}
		<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
			<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-prevu-total">Prévu total</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[-2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[-1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small tr-connected-user">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[0],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-prevu-total">{{round($user_prevu_total_array[3],1)}}</div>
			</div>
		</div>

		{{--REALISE--}}
		<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
			<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-realise">Réalisé</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[-2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[-1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small tr-connected-user">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[0],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise">{{round($user_realise_array[3],1)}}</div>
			</div>
		</div>	

		{{--RESTANT--}}
		<div class="dashboard-column flex-basis-20 flex-grow-1 flex-shrink-1">
			<div class="dashboard-subject marg-v-bottom-medium ln-b-solid-small style-realise-light no-bold">Restant</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[-2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(-1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[-1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small tr-connected-user">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(0)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[0],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+1)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[1],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+2)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[2],1)}}</div>
			</div>
			<div class="dashboard-card pad-v-small">
				<div class="dashboard-label">{{ucfirst(\Carbon\Carbon::parse($current_date)->addMonth(+3)->localeMonth).' '.\Carbon\Carbon::parse($current_date)->addMonth(-2)->year}}</div>
				<div class="dashboard-data style-realise-light no-bold">{{round($user_restant_array[3],1)}}</div>
			</div>
		</div>		
	</div>
	
<div class="text-center table-separator"></div>
