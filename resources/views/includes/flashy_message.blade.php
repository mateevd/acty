@if(Session::has('flashy_notification.message'))
	<script id="flashy-template" type="text/template">
		<div class="flashy flashy--{{ Session::get('flashy_notification.type') }}">
			<i class="flashy__body"></i>
		</div>
	</script>
@endif