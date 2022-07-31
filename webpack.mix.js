const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .extract()
    .vue()
    //.postCss('resources/css/app.css', 'public/css', [require('tailwindcss'), require('autoprefixer')])
    .sass('resources/sass/_defaults.scss', 'public/css')
    .sass('resources/sass/HeaderLayout.scss', 'public/css')
    .sass('resources/sass/MobileNavigation.scss', 'public/css')
    .sass('resources/sass/loading.scss', 'public/css')
    .sass('resources/sass/HomeGuest.scss', 'public/css')
    .sass('resources/sass/HomeSections.scss', 'public/css')
    .sass('resources/sass/HomeHero.scss', 'public/css')
    .sass('resources/sass/LoginPage.scss', 'public/css')
    .sass('resources/sass/RegistrationPage.scss', 'public/css')
    .sass('resources/sass/EmailVerifyNoticeLayout.scss', 'public/css')
    .sass('resources/sass/ForgotPassowrd.scss', 'public/css')
    .sass('resources/sass/Dashboard.scss', 'public/css')
    .sass('resources/sass/SearchEngine.scss', 'public/css')
    .sass('resources/sass/DashLeftPanelLayout.scss', 'public/css')
    .sass('resources/sass/RemindersHeader.scss', 'public/css')
    .sass('resources/sass/RemindersList.scss', 'public/css')
    .sass('resources/sass/ReminderEditModules.scss', 'public/css')
    .sass('resources/sass/IgnoredsList.scss', 'public/css')
    .sass('resources/sass/ReminderUserView.scss', 'public/css')
    .sass('resources/sass/NotificationsList.scss', 'public/css')
    .sass('resources/sass/UserProfile.scss', 'public/css')
    .sass('resources/sass/SecuritySettings.scss', 'public/css')
    .alias({
        '@': 'resources/js',
    });

if (mix.inProduction()) {
    mix.version();
}



mix.disableNotifications();