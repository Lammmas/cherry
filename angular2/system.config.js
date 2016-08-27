(function (global) {
    "use strict";

    // Most environments should use UMD; some (Karma) need the individual index files
    var setPackageConfig, config,
        map = {
            'app':                 'build',
            'rxjs':                'node_modules/rxjs',
            '@angular':            'node_modules/@angular',
            'angular2-cookie':     'node_modules/angular2-cookie',
            'angular2-highcharts': 'angular2-highcharts/dist'
        }, packages = {
            'app':                 {main: 'main.js', defaultExtension: 'js'},
            'rxjs':                {defaultExtension: 'js'},
            'angular2-cookie':     {main: 'core.js', defaultExtension: 'js'},
            'angular2-highcharts': {main: 'index', format: 'cjs', defaultExtension: 'js'}
        }, angularPackages = [
            'common',
            'compiler',
            'core',
            'forms',
            'http',
            'platform-browser',
            'platform-browser-dynamic',
            'router'
        ];

    // Individual files (~300 requests):
    function packIndex(pkgName) {
        packages['@angular/' + pkgName] = {main: 'index.js', defaultExtension: 'js'};
    }

    // Bundled (~40 requests):
    function packUmd(pkgName) {
        packages['@angular/' + pkgName] = {main: '/bundles/' + pkgName + '.umd.js', defaultExtension: 'js'};
    }

    setPackageConfig = System.packageWithIndex ? packIndex : packUmd;

    // Add package entries for angular packages
    angularPackages.forEach(setPackageConfig);

    config = {
        map: map,
        packages: packages
    };

    System.config(config);
}) (this);

