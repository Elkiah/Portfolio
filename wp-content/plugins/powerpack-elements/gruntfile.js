/* jshint node:true */
module.exports = function( grunt ) {
	'use strict';

	const pluginName = 'powerpack-elements';
	const textDomain = 'powerpack';
	const buildPath = 'build/' + pluginName + '/';
	const sass = require( 'node-sass' );
	const pkg = grunt.file.readJSON( 'package.json' );

	grunt.initConfig({
		pkg,

		// Setting folder templates.
		dirs: {
			css: 'assets/css',
			js: 'assets/js'
		},

		// JavaScript linting with JSHint.
		jshint: {
			options: {
				jshintrc: 'config/.jshintrc'
			},
			all: [
				'<%= dirs.js %>/admin/*.js',
				'!<%= dirs.js %>/admin/*.min.js',
				'<%= dirs.js %>/frontend/*.js',
				'!<%= dirs.js %>/frontend/*.min.js'
			]
		},

		// Sass linting with Stylelint.
		stylelint: {
			options: {
				configFile: 'config/.stylelintrc'
			},
			all: [
				'<%= dirs.css %>/*.scss'
			]
		},

		// Minify .js files.
		uglify: {
			options: {
				ie8: true,
				parse: {
					strict: false
				},
				output: {
					comments : /@license|@preserve|^!/
				}
			},
			admin: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/admin/',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: '<%= dirs.js %>/admin/',
					ext: '.min.js'
				}]
			},
			frontend: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/frontend/',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: '<%= dirs.js %>/frontend/',
					ext: '.min.js'
				}]
			},
		},

		// Compile all .scss files.
		sass: {
			compile: {
				options: {
					implementation: sass,
					sourceMap: 'none'
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>/',
					src: ['*.scss'],
					dest: '<%= dirs.css %>/',
					ext: '.css'
				}]
			}
		},

		// Autoprefixer.
		postcss: {
			options: {
				processors: [
					require( 'autoprefixer' )
				]
			},
			dist: {
				src: [
					'<%= dirs.css %>/*.css'
				]
			}
		},

		// Minify all .css files.
		cssmin: {
			minify: {
				files: [
					{
						expand: true,
						cwd: '<%= dirs.css %>/',
						src: ['*.css'],
						dest: '<%= dirs.css %>/',
						ext: '.css'
					}
				]
			}
		},

		// Watch changes for assets.
		watch: {
			css: {
				files: ['<%= dirs.css %>/*.scss'],
				tasks: ['sass', 'postcss', 'cssmin']
			},
			js: {
				files: [
					'<%= dirs.js %>/admin/*js',
					'<%= dirs.js %>/frontend/*js',
					'!<%= dirs.js %>/admin/*.min.js',
					'!<%= dirs.js %>/frontend/*.min.js'
				],
				tasks: ['jshint', 'uglify']
			}
		},

		// Add text-domain.
		addtextdomain: {
			options: {
				textdomain: textDomain,
				updateDomains: ['ibx-admin']  // List of text domains to replace.
			},
			target: {
				files: {
					src: [
						'*.php',
						'**/*.php',
						'!node_modules/**',
						'!vendor/**',
					]
				}
			}
		},

		// Check textdomain errors.
		checktextdomain: {
			options:{
				text_domain: textDomain,
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			files: {
				src:  [
					'**/*.php',               // Include all files
					'!node_modules/**',       // Exclude node_modules/
					'!vendor/**',             // Exclude vendor/
				],
				expand: true
			}
		},

		// Generate POT files.
		makepot: {
			options: {
				type: 'wp-plugin',
				domainPath: 'i18n/languages',
				potHeaders: {
					'report-msgid-bugs-to': 'https://github.com/helloideabox/ibx-admin/issues',
					'language-team': 'LANGUAGE <EMAIL@ADDRESS>'
				}
			},
			dist: {
				options: {
					potFilename: 'ibx-admin.pot',
					exclude: [
						'node_modules/.*',
						'vendor/.*',
					]
				}
			}
		},

		// PHP Code Sniffer.
		phpcs: {
			options: {
				bin: 'vendor/bin/phpcs'
			},
			dist: {
				src:  [
					'**/*.php', // Include all php files.
					'!node_modules/**',
					'!vendor/**'
				]
			}
		},

		copy: {
            main: {
                expand: true,
                src: [
					'**',
					'!.gitignore',
					'!.gitattributes',
					'!.editorconfig',
					'!.jshintrc',
					'!.stylelintrc',
					'!*.sh',
					'!*.map',
					'!*.zip',
                    '!Gruntfile.js',
                    '!package.json',
					'!README.md',
					'!codesniffer.ruleset.xml',
					'!ruleset.xml',
                    '!composer.json',
                    '!composer.lock',
                    '!package-lock.json',
                    '!phpcs.xml.dist',
                    '!phpcs.xml',
                    '!node_modules/**',
                    '!.git/**',
                    '!bin/**',
					'!vendor/**',
					'!build/**',
					'!assets/*.scss',
					'!assets/**/*.map',
					'!*~'
                ],
                dest: buildPath
            }
		},
		
		compress: {
            main: {
                options: {
                    archive: pluginName + '.zip',
                    mode: 'zip'
                },
                files: [
                    {
						cwd: 'build/',
						expand: true,
                        src: [
                            '**'
                        ]
                    }
                ]
            }
        },

		clean: {
            main: ['build'],
            zip: ['*.zip']
        },

		bumpup: {
            options: {
                updateProps: {
                    pkg: 'package.json'
                }
            },
            file: 'package.json'
        },

		// Replace.
		replace: {
			main: {
				src: ['ibx-admin.php'],
				overwrite: true,
				replacements: [
					{
						from: /(Version:\s+)(\d+(\.\d+){0,3})([^\n^\.\d]?.*?)(\n)/,
						to: 'Version: <%= pkg.version %>\n'
					},
					{
						from: /IBX_ADMIN_UI_VER', '.*?'/g,
						to: 'IBX_ADMIN_UI_VER\', \'<%= pkg.version %>\''
					},
				]
			},

			comments: {
				src: [
					'*.php',
                    '**/*.php',
					'!node_modules/**',
					'!vendor/**',
					'!i18n/**',
					'!build/**'
				],
				overwrite: true,
				replacements: [
					{
						from: 'x.x.x',
						to: '<%= pkg.version %>'
					}
				]
			}
		}
	});

	// Load NPM tasks to be used here.
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-rtlcss' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-stylelint' );
	grunt.loadNpmTasks( 'grunt-checktextdomain' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-bumpup' );
	grunt.loadNpmTasks( 'grunt-text-replace' );

	// Register tasks.
	grunt.registerTask( 'default', [
		'js',
		'css',
		'i18n'
	] );

	grunt.registerTask( 'js', [
		'jshint',
		'uglify:admin',
		'uglify:frontend'
	] );

	grunt.registerTask( 'css', [
		'sass',
		'rtlcss',
		'postcss',
		'cssmin'
	] );

	grunt.registerTask( 'assets', [
		'js',
		'css'
	] );

	grunt.registerTask( 'i18n', [
		'addtextdomain',
		'checktextdomain',
		'makepot'
	] );

	// Bump Version - `grunt version-bump --ver=<version-number>`
    grunt.registerTask( 'version-bump', function (ver) {
        var version = grunt.option( 'ver' );

        if ( version ) {
            version = version ? version : 'patch';

            grunt.task.run( 'bumpup:' + version );
            grunt.task.run( 'replace' );
        } else {
			throw new Error( 'Provide version with parameter --ver.' );
		}
	} );
	
	// Release.
    grunt.registerTask( 'release', [
		'clean:zip',
		'copy:main',
		'compress:main',
		'clean:main'
	] );
};