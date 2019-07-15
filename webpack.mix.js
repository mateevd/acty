let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
	// .js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/main.js', 'public/js')
	.js('resources/assets/js/absences.js', 'public/js')
	.js('resources/assets/js/activities.js', 'public/js')
	.js('resources/assets/js/charges.js', 'public/js')
	.js('resources/assets/js/configs.js', 'public/js')
	.js('resources/assets/js/dashboard.js', 'public/js')
	.js('resources/assets/js/dragdrop.js', 'public/js')
	.js('resources/assets/js/info.js', 'public/js')
	.js('resources/assets/js/log_activity.js', 'public/js')
	.js('resources/assets/js/login.js', 'public/js')
	.js('resources/assets/js/phases.js', 'public/js')
	.js('resources/assets/js/planning.js', 'public/js')
	.js('resources/assets/js/tasks.js', 'public/js')
	.js('resources/assets/js/users.js', 'public/js')
	.js('resources/assets/js/work_days.js', 'public/js')

   // .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/dragdrop.scss', 'public/css')
   .sass('resources/assets/sass/flashy.scss', 'public/css')
   .sass('resources/assets/sass/theme_01.scss', 'public/css')
   .sass('resources/assets/sass/theme_02.scss', 'public/css')
   .sass('resources/assets/sass/theme_03.scss', 'public/css')
   .sass('resources/assets/sass/zoom_75.scss', 'public/css')
   .sass('resources/assets/sass/zoom_100.scss', 'public/css')
   .sass('resources/assets/sass/zoom_125.scss', 'public/css')
   .sass('resources/assets/sass/custom.scss', 'public/css');
