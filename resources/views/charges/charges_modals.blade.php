{{--MODAL DETAILS CHARGES--}}
@if(isset($entity_charge) || isset($task))
<div class="modal" id="detailsCharges" tabindex="-1" role="dialog" aria-labelledby="{{trans('charges.lab_simple_view')}}">
	<div class="modal-dialog modal-largest" role="document">
		<div class="modal-content">
			<div class="modal-custom-title">{{trans('charges.lab_simple_view')}}</div>
			<div class="modal-custom-libelle" id="full_name"></div>

			<div class="flex-row flex-wrap-no justify-center items-center text-center pad-v-small marg-none modal-custom-libelle-ex has-search-sticky">
				<i class="fas fa-magic svg-inside-details"></i>
				<input class="form-control universal-filter" id="chargesDetailsInput" type="text"
				       placeholder="Filtre universel" tabindex="1">
				<i class="modal-custom-btn-horizontal" tabindex="2"
				   title="{{trans('app.back')}}"
				   data-dismiss="modal"
				   data-toggle="tooltip"
				   data-placement="bottom">
					<i class="far fa-times-circle"></i>
				</i>
			</div>

			<form action="{{route('charges.details', [$entity_charge->user_id, $entity_charge->mm, $entity_charge->yy])}}" method="get"
			      class="needs-validation hide-submit modal-custom-form" novalidate>
				{{csrf_field()}}
				<input type="hidden" name="charge_month" id="charge_month">
				<input type="hidden" name="charge_year" id="charge_year">
				@include('charges.charges_detail')

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