@extends('layouts.app')

@section('content')

@if (session('status'))<div class="alert alert-success" role="alert">{{ session('status') }}</div>@endif

{{--SELECT TAB--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="log_activity-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#log_activity" role="tab" data-toggle="tab">
					<div>Journal</div>
				</a>
			</li>
		</ul>
	</div>
	@include('includes.date_select')
</div>

<div class="tab-content">
	{{--PLANNING--}}
	<div class="tab-pane" role="tabpanel" id="log_activity">

		<div class="table-responsive">
			<table class="table sortable">
				<thead>
				<tr class="text-center">
					<th class="tiny-cell action-btn-no-header" data-defaultsort="desc">{{trans('activityLog.ID')}}</th> 
					<th class="tiny-cell">{{trans('activityLog.Subject')}}</th>
					<th class="tiny-cell">{{trans('activityLog.Function')}}</th>
					<th class="tiny-cell">{{trans('activityLog.Ip')}}</th>
					<th class="tiny-cell">{{trans('activityLog.User_id')}}</th>
					<th class="tiny-cell">{{trans('activityLog.Date')}}</th>
					<th class="text-left">{{trans('activityLog.lab_data')}}</th>
				</tr>
				</thead>
				<tbody class="scroll" id="filter_table">
				@foreach($logs as $key => $log)
					<tr class="text-center">
						<td class="action-btn-no-body">{{ $log->id }}</td>
						<td class="">{{ $log->subject }}</td>
						<td class="">{{ $log->function }}</td>
						<td class="">{{ $log->ip }}</td>
						<td class="">{{ $log->user_id }}</td>
						<td class="">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</td>
						<td class="text-left truncate-activites">{{ $log->data }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	
		{{ $logs->links() }}
		<div class="text-center table-separator"></div>
	</div>
</div>
@endsection
