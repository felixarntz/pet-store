/* ---- THE FOLLOWING CONFIG SHOULD BE EDITED ---- */

var pkg = require( './package.json' );

function parseKeywords( keywords ) {
	// These keywords are useful for Packagist/NPM/Bower, but not for the WordPress plugin repository.
	var disallowed = [ 'wordpress', 'plugin' ];

	k = keywords;
	for ( var i in disallowed ) {
		var index = k.indexOf( disallowed[ i ] );
		if ( -1 < index ) {
			k.splice( index, 1 );
		}
	}

	return k;
}

var keywords = parseKeywords( pkg.keywords );

var config = {
	textdomain: pkg.name,
	themeURI: pkg.homepage,
	author: pkg.author.name,
	authorURI: pkg.author.url,
	description: pkg.description,
	version: pkg.version,
	license: pkg.license.name,
	licenseURI: pkg.license.url,
	tags: keywords.join( ', ' ),
	translateURI: pkg.homepage
};

/* ---- DO NOT EDIT BELOW THIS LINE ---- */


/* ---- REQUIRED DEPENDENCIES ---- */

var gulp = require( 'gulp' );

var sort = require( 'gulp-sort' );
var wpPot = require( 'gulp-wp-pot' );

var php = {
	files: [ './*.php' ]
};

/* ---- MAIN TASKS ---- */

gulp.task( 'default', [ 'pot' ]);

// generate POT file
gulp.task( 'pot', function( done ) {
	gulp.src( php.files, { base: './' })
		.pipe( sort() )
		.pipe( wpPot({
			domain: config.textdomain,
			destFile: './languages/' + config.textdomain + '.pot',
			headers: {
				'report-msgid-bugs-to': config.translateURI,
				'x-generator': 'gulp-wp-pot',
				'x-poedit-basepath': '.',
				'x-poedit-language': 'English',
				'x-poedit-country': 'UNITED STATES',
				'x-poedit-sourcecharset': 'uft-8',
				'x-poedit-keywordslist': '__;_e;_x:1,2c;_ex:1,2c;_n:1,2; _nx:1,2,4c;_n_noop:1,2;_nx_noop:1,2,3c;esc_attr__; esc_html__;esc_attr_e; esc_html_e;esc_attr_x:1,2c; esc_html_x:1,2c;',
				'x-poedit-bookmars': '',
				'x-poedit-searchpath-0': '.',
				'x-textdomain-support': 'yes'
			}
		}) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', done );
});
