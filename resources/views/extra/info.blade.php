@extends('layouts.app')

@section('content')

{{--SELECT TAB--}}
<div class="navbar-tabs">
	<div class="navbar-tabs-select-tab">
		<ul class="nav" role="tablist" id="info-tab-selection">
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#task_types" role="tab" data-toggle="tab">
					{{trans('app.info_task_types')}}</a>
			</li>
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#open_days" role="tab" data-toggle="tab">
					{{trans('app.info_open_days')}}</a>
			</li>
			<li class="pad-h-right-small">
				<a class="flex-row flex-wrap-no nav-link-period" href="#change_log" role="tab" data-toggle="tab">
					{{trans('app.info_change_log')}}</a>
			</li>
		</ul>
	</div>
	<div class="navbar-tabs-select-date">
		<form id="date_change" action="{{route('info')}}" method="get" name="dateSelect" class="hide-submit">
			<div class="">
				{!! Form::selectMonth('monthSelect', $current_date->month ? $current_date->month : Carbon\Carbon::now()->month , 
						["class"=>"drop-date-common drop-date-month"])!!}
			</div>
			<div class="">
				{!! Form::selectRange('yearSelect', config('constants.start_year'), config('constants.end_year'), $current_date->year ? $current_date->year : Carbon\Carbon::now()->year, 
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
</div>

<div class="tab-content">

	{{--TASK TYPES--}}
	<div class="tab-pane" role="tabpanel" id="task_types">
		<div class="table-responsive">
			<table class="table sortable">
				<thead>
				<tr>
					<th class="tiny-cell">{{trans('app.info_task_type')}}</th>
					<th>{{ucfirst(trans('app.description'))}}</th>
				</tr>
				</thead>

				<tbody id="filter_table">
				@foreach($task_types as $task_type)
					<tr>
						<td class="wrap-no">{{$task_type->task_name}}</td>
						<td class="truncate-activites">{{$task_type->task_description}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="text-center total-separator"></div>
	</div>

	{{--TAB OPEN DAYS--}}
	<div class="tab-pane" role="tabpanel" id="open_days">
		<div class="table-responsive">
			<table class="table sortable">
				<thead>
				<tr>
					<th class="tiny-cell">{{ucfirst(trans('app.lab_year'))}}</th>
					<th class="tiny-cell text-center">{{ucfirst(trans('app.lab_month'))}}</th>
					<th>{{trans('app.info_open_days')}}</th>
				</tr>
				</thead>

				<tbody id="filter_table">
				@foreach($open_days as $open_day)
					<tr class="@if ( ($open_day->year == Carbon\Carbon::now()->year) && ($open_day->month == Carbon\Carbon::now()->month) ) tr-connected-user @endif">
						<td>{{$open_day->year}}</td>
						<td class="text-center">{{$open_day->month}}</td>
						<td>{{$open_day->days}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="text-center total-separator"></div>
	</div>

	{{--CHANGE LOG--}}
	<div class="tab-pane" role="tabpanel" id="change_log">
		<div class="table-responsive">
			<table class="table sortable">
				<thead>
				<tr>
					<th class="tiny-cell">Version</th>
					<th>{{ucfirst(trans('app.description'))}}</th>
				</tr>
				</thead>

				<tbody id="filter_table">
					<tr class="tr-connected-user">
						<td class="wrap-no v-align-top">BUILD.1907 - REV.1301</td>
						<td class="">
							<span class="bold underline">Changements majeurs</span>
							<ul>
								<li>Mise en place des CRAs (fonctionnalité optionnelle)</li>
							</ul>
							<hr>
							<span class="bold underline">Changements mineurs</span>
							<ul>
								<li>Standardisation des pages :</li>
								<ul>
									<li>Le choix du mois en cours s'affiche désormais sur toutes les pages</li>
									<li>Toutes les pages ont désormais au moins un onglet</li>
									<li>Les totaux des écrans sont désormais dans la partie supérieure</li>
									<li>Affichage du tri par défaut sur les chargements des tableaux</li>
								</ul>
								<li>Refonte de l'écran <b>Tâches</b> : 
								<ul>
									<li>Édition, suppression et réactivation des tâches</li>
									<li>Rajout de l'onglet entité</li>
								</ul>
								<li>Refonte mineure de l'écran <b>Charges</b> :
								<ul>
									<li>Rajout d'une icône lorsque toutes les tâches du mois sont validées</li>
									<li>Possibilité de voir la charge en cliquant sur les pastilles</li>
								</ul>
								<li>Refonte mineure de l'écran <b>Planning</b> :
								<ul>
									<li>Légère refonte graphique pour une meilleure prise en compte des thèmes</li>
									<li>Rajout de boutons globaux Terminer et Réactiver (avec la selection des tâches)</li>
								</ul>
								<li>Refonte mineure de l'écran <b>Indicateurs</b> : 
								<ul>
									<li>Rajout d'onglets</li>
									<li>Meilleure distinction des données</li>
									<li>Structuration des données compatibles avec les évolutions futures</li>
								</ul>
								<li>L'export permet maintenant aux utilisateurs de récupérer leurs temps réalisés sur une période donnée</li>
								<li>Optimisation du moteur de mise à jour en cascade des tâches, phases et activités</li>
								<li>Le bouton d'aide se trouve désormais dans le menu utilisateur, en haut, à doite</li>
								<li>Rajout des thèmes "Printanier" et "Pourpre"</li>
							</ul>
							<hr>
							<span class="bold underline">Correction de bugs</span>
							<ul>
								<li>1907-0052 : Correction dans l'affichage des boutons de rajout/suppression (temps et absences)</li>
								<li>1907-0051 : Correction dans les autorisations liées au responsable adjoint</li>
								<li>1907-0049 : Correction dans l'affichage du pied de page</li>
								<li>1906-0044 : Correction dans l'icône de tri des tableaux qui était à l'envers</li>
								<li>1906-0043 : Correction dans le tri de la liste des phase pour le déplacement et la copie multi-tâches</li>
							</ul>
						</td>
					</tr>
					<tr class="">
						<td class="wrap-no v-align-top">BUILD.1906 - REV.0601</td>
						<td class="">
							<span class="bold underline">Changements mineurs</span>
							<ul>
								<li>Modification du sélecteur d'affectation d'utilisateurs dans la création d'un tâche (écran de planification)</li>
								<li>Rajout d'un export personnalisé : liste globale des activités, charges globales sur une période</li>
							</ul>
							<hr>
							<span class="bold underline">Correction de bugs</span>
							<ul>
								<li>1905-0031 : Correction dans l'appel d'une page qui pouvait laisser l'icône de chargement active</li>
							</ul>
						</td>
					</tr>
					<tr class="">
						<td class="wrap-no v-align-top">BUILD.1905 - REV.2801</td>
						<td class="">
							<span class="bold underline">Changements mineurs</span>
							<ul>
								<li>Suppression du mail comme identifiant à l'application au profit d'un login</li>
								<li>Rajout d'une page de configuration (changement du mot de passe, du thème et du zoom)</li>
								<li>Déplacement de la page de changement du mot de passe</li>
								<li>Les données sur le Réalisé dans la page <b>Indicateurs</b> sont désormais liées à la date contextuelle de l'application (affichée sous le trigramme)</li>
								<li>Le nombre de jours restants à réaliser dans le mois a été supprimé du menu <b>Tâches</b></li>
								<li>Le nombre de jours restants à réaliser dans le mois se trouve désormais uniquement dans le menu <b>Temps</b></li>
							</ul>
							<hr>
							<span class="bold underline">Correction de bugs</span>
							<ul>
								<li>1905-0022 : Correction dans l'affichage d'un commentaire trop long et sans retour chariot qui empêchait de voir la fin du tableau</li>
								<li>1905-0019 : Correction dans la création d'un utilisateur</li>
								<li>1905-0015 : Correction dans le calcul de la date de fin d'une tâche dans le cas de tâches récurrentes</li>
							</ul>
						</td>
					</tr>
					<tr class="">
						<td class="wrap-no v-align-top">BUILD.1905 - REV.1701</td>
						<td class="">
							<span class="bold underline">Changements mineurs</span>
							<ul>
								<li>possibilité de sauvegarder l'onglet sélectionné dans les pages (se réinitialise à la fermeture du navigateur)</li>
							</ul>
							<hr>
							<span class="bold underline">Correction de bugs</span>
							<ul>
								<li>1905-0013 : Correction dans l'affichage des phases (pliage/dépliage)</li>
								<li>1905-0011 : Correction du problème de tri de la colonne Restant dans la page <b>Temps</b></li>
							</ul>
						</td>
					</tr>
					<tr class="">
						<td class="wrap-no v-align-top">BUILD.1905 - REV.1501</td>
						<td class="">
							<span class="bold underline">Changements majeurs</span>
							<ul>
								<li>Refonte du site en responsive design</li>
								<li>Refonte du moteur d'alimentation des pages php (controlleurs)</li>
								<li>La date choisie (dans les pages <b>Absences</b>, <b>Tâches</b>, <b>Charges</b> et <b>Temps</b>) reste persistante</li>
							</ul>
							<hr>
							<span class="bold underline">Changements mineurs</span>
							<ul>
								<li>L'objet <b>Projet</b> a été remplacé par l'objet <b>Activité</b></li>
								<li>La notion de Correspondant a été remplacée par celle de Responsable adjoint</li>
								<li>Onlget rajouté sur la partie activité</li>
								<li>Les activités sont précédées d'étoiles indiquant les droits (respectivement dans l'ordre de remplissage de l'étoile) : hérités, de responsable ou d'adjoint</li>
								<li>Il est possible désormais de déplacer une phase</li>
								<li>Il est possible désormais dans la page Charges en cliquant sur l'icône de charge de voir toutes les activités prévues de la personne pour le mois sélectionné</li>
								<li>Les pages <b>Absences</b>, <b>Tâches</b> et <b>Temps</b> possèdent désormais des détails supplémentaires affichés en fin de tableau</li>
							</ul>
							<hr>
							<span class="bold underline">Correction de bugs</span>
							<ul>
								<li>1905-0007 : Correction dans les calculs en cascade (Prévu et Réalisé)</li>
								<li>1905-0005 : Correction dans le calcul des charges</li>
								<li>1905-0004 : Correction dans l'affichage des phases</li>
							</ul>
						</td>
					</tr>

				</tbody>
			</table>
		</div>
		<div class="text-center total-separator"></div>
	</div>


</div>

@endsection
