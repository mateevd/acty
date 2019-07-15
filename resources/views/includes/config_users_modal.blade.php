<div class="modal" id="changeSettings" tabindex="-1" role="dialog" aria-labelledby="Configuration">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{trans('users.lab_settings')}}</div>

			<form action="{{route('users.settings')}}" method="post" class="hide-submit modal-custom-form">
				{{method_field('post')}}
				{{csrf_field()}}

				<label for="theme">Theme</label>
				<select name="theme" id="theme" class="option-select form-control modal-custom-fields-input">
					<option value="0">{{config('constants.theme_main')}}</option>
					<option value="1">{{config('constants.theme_dark')}}</option>
				</select>

				{{--<label for="lang">Lang</label>
				<select name="lang" id="lang" class="option-select form-control modal-custom-fields-input">
					<option value="0">{{config('constants.lang_fr')}}</option>
					<option value="1">{{config('constants.lang_en')}}</option>
				</select>--}}

				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
					        data-dismiss="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button class="modal-custom-btn-horizontal apply-spin" tabindex="101"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.ok')}}">
						<i class="far fa-check-circle"></i>
					</button>
				</div>

			</form>
		</div>
	</div>
</div>