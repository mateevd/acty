<div class="navbar-tabs-select-date">
	<form id="date_change" action="{{url('/dateSelect')}}" method="get" name="dateSelect" class="hide-submit">
		<div class="">
			{!! Form::selectMonth('monthSelect', session()->has('current_month') ? session()->get('current_month') : Carbon\Carbon::now()->month ,
					["class"=>"drop-date-common drop-date-month"])!!}
		</div>
		<div class="">
			{!! Form::selectRange('yearSelect', config('constants.start_year'), config('constants.end_year'), session()->has('current_year') ? session()->get('current_year') : Carbon\Carbon::now()->year,
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