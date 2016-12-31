var gulp = require('gulp');
var elixir = require('laravel-elixir');
var argv = require('yargs').argv;

elixir.config.assetsPath = 'source/_assets';
elixir.config.publicPath = 'source';

elixir(function(mix) {
    var env = argv.e || argv.env || 'local';
    var port = argv.p || argv.port || 3000;

    mix.copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.eot',
        'source/fonts'
    ).copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.ttf',
        'source/fonts'
    ).copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.woff',
        'source/fonts'
    ).copy(
       'node_modules/bootstrap-sass/assets/fonts/bootstrap/glyphicons-halflings-regular.woff2',
       'source/fonts'
    );

    mix.sass('main.scss')
        .exec('jigsaw build ' + env, ['./source/*', './source/**/*', '!./source/_assets/**/*'])
        .browserify('app.js')
        .browserSync({
            port: port,
            server: { baseDir: 'build_' + env },
            proxy: null,
            files: [ 'build_' + env + '/**/*' ]
        });
});