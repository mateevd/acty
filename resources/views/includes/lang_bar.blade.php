<select class="custom-select" name="" id="languageSwitcher">
	<option value="">{{trans('app.Language')}}</option>
	<option value="en"> <?php $cuRRlocal = Config::get('app.locale'); ?> English</option>
	<option value="fr"> <?php $cuRRlocal = Config::get('app.locale'); ?> Français</option>
</select>