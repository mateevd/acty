{{--CREATE USER--}}
<div class="modal" id="createUser" tabindex="-1" role="dialog" aria-labelledby="{{trans('users.act_create')}}">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{trans('app.add')}}</div>
			<div class="modal-custom-libelle">{{trans('app.user')}}</div>

			<form id="user_create" action="{{route('users.create')}}" method="post"
			      class="needs-validation hide-submit modal-custom-form" novalidate autocomplete="off">
				{{csrf_field()}}

				@include('users.user_form')

				<div class="modal-custom-bottom">
					<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
					        data-dismiss="modal"
					        data-toggle="tooltip"
					        data-placement="bottom"
					        title="{{trans('app.back')}}">
						<i class="far fa-times-circle"></i>
					</button>
					<button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
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

{{--EDIT USER--}}
@if(isset($user))
	<div class="modal" id="editUser" tabindex="-1" role="dialog" aria-labelledby="{{trans('users.act_edit')}}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{ucfirst(trans('app.Edit'))}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>

				<form id="user_update" action="{{route('users.update')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate autocomplete="off">
					{{method_field('put')}}
					{{csrf_field()}}
					<input type="hidden" name="user_id" id="user_id" value="">

					@include('users.user_form_edit')

					<div class="modal-custom-detail style-ids" id="user_id"></div>
					<div class="modal-custom-bottom">
						<button type="button" class="modal-custom-btn-horizontal" tabindex="100"
						        data-dismiss="modal"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        title="{{trans('app.back')}}">
							<i class="far fa-times-circle"></i>
						</button>
						<button id="btn-submit-form"  class="modal-custom-btn-horizontal" tabindex="101"
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
@endif

{{--DEACTIVATE USER--}}
@if(isset($user))
	<div class="modal" id="terminateUser" tabindex="-1" role="dialog" aria-labelledby="{{trans('users.act_deactivate')}}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{trans('app.deactivate')}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>
				<div class="modal-custom-description">{{trans('users.act_deactivate_confirm')}}</div>
				<form action="{{route('users.terminate')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id" id="user_id" value="">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
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
@endif

{{--ACTIVATE USER--}}
@if(isset($user))
	<div class="modal" id="activateUser" tabindex="-1" role="dialog" aria-labelledby="{{trans('users.act_activate')}}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{trans('app.activate')}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>
				<div class="modal-custom-description">{{trans('users.act_activate_confirm')}}</div>

				<form action="{{route('users.activate')}}" method="post"
				      class="needs-validation hide-submit modal-custom-form" novalidate>
					{{method_field('post')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id" id="user_id" value="">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
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
@endif

{{--DELETE USER--}}
@if(isset($user))
	<div class="modal" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="{{trans('users.act_delete')}}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-custom-title">{{trans('app.delete')}}</div>
				<div class="modal-custom-libelle" id="user_name"></div>
				<div class="modal-custom-description">{{trans('users.act_delete_confirm')}}</div>

				<form action="{{route('users.destroy')}}" method="post" class="hide-submit modal-custom-form">
					{{method_field('delete')}}
					{{csrf_field()}}

					<input type="hidden" name="user_id" id="user_id">

					<div class="modal-custom-detail style-ids" id="user_id"></div>
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
@endif