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
	.js('resources/assets/js/main.js', 'public/js')

	// .sass('resources/assets/sass/app.scss', 'public/css')
	.sass('resources/assets/sass/custom.scss', 'public/css')
	.sass('resources/assets/sass/dragdrop.scss', 'public/css')
	.sass('resources/assets/sass/flashy.scss', 'public/css')
	.sass('resources/assets/sass/theme_01.scss', 'public/css')
	.sass('resources/assets/sass/theme_02.scss', 'public/css')
	.sass('resources/assets/sass/theme_03.scss', 'public/css')
	.sass('resources/assets/sass/zoom_75.scss', 'public/css')
	.sass('resources/assets/sass/zoom_100.scss', 'public/css')
	.sass('resources/assets/sass/zoom_125.scss', 'public/css');