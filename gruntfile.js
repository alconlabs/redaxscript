module.exports = function (grunt)
{
	'use strict';

	/* config grunt */

	grunt.initConfig(
	{
		watch:
		{
			scripts:
			{
				files: ['<%=jshint.base%>', '<%=jshint.modules%>', '<%=jshint.templates%>'],
				tasks: ['jshint']
			},
			styles:
			{
				files: ['<%=csslint.base.src%>', '<%=csslint.modules.src%>', '<%=csslint.templates.src%>'],
				tasks: ['csslint']
			}
		},
		jshint:
		{
			dependency: ['gruntfile.js'],
			base: ['scripts/*.js'],
			modules: ['modules/*/scripts/*.js'],
			templates: ['templates/*/scripts/*.js'],
			options:
			{
				jshintrc: '.jshintrc'
			}
		},
		jsonlint:
		{
			dependency: ['composer.json', 'package.json']
		},
		qunit:
		{
			jquery:
			{
				options:
				{
					urls: ['http://develop.redaxscript.com/qunit.default']
				}
			},
			zepto:
			{
				options:
				{
					urls: ['http://develop.redaxscript.com/qunit.zepto']
				}
			}
		},
		csslint:
		{
			base:
			{
				src: ['styles/*.css', '!styles/webkit.css']
			},
			modules:
			{
				src: ['modules/*/styles/*.css']
			},
			templates:
			{
				src: ['templates/*/styles/*.css']
			},
			options:
			{
				csslintrc: '.csslintrc'
			}
		},
		htmlhint:
		{
			modules:
			{
				src: ['modules/**/*.phtml']
			},
			templates:
			{
				src: ['templates/**/*.phtml']
			},
			options:
			{
				htmlhintrc: '.htmlhintrc'
			}
		},
		phpcs:
		{
			base:
			{
				dir: 'includes'
			},
			languages:
			{
				dir: 'languages'
			},
			modules:
			{
				dir: 'modules'
			},
			templates:
			{
				dir: 'templates'
			},
			options:
			{
				bin: 'vendor/bin/phpcs',
				standard: 'Redaxscript'
			}
		},
		lineending:
		{
			css:
			{
				files:
				[
					{
						src: ['styles/*.css', 'modules/*/styles/*.css', 'templates/*/styles/*.css'],
						expand: true
					}
				]
			},
			js:
			{
				files:
				[
					{
						src: ['*.js', 'scripts/*.js', 'modules/*/scripts/*.js', 'templates/*/scripts/*.js'],
						expand: true
					}
				]
			},
			phtml:
			{
				files:
				[
					{
						src: ['modules/**/*.phtml', 'templates/**/*.phtml'],
						expand: true
					}
				]
			},
			php:
			{
				files:
				[
					{
						src: ['*.php', 'includes/**/*.php', 'languages/*.php', 'modules/**/*.php'],
						expand: true
					}
				]
			},
			options:
			{
				eol: 'crlf'
			}
		},
		shell:
		{
			tocBase:
			{
				command: 'php vendor/tocgen/tocgen.php scripts .tocgen && php vendor/tocgen/tocgen.php styles .tocgen'
			},
			tocModules:
			{
				command: 'php vendor/tocgen/tocgen.php modules .tocgen -r'
			},
			tocTemplates:
			{
				command: 'php vendor/tocgen/tocgen.php templates .tocgen -r'
			},
			svgoTemplates:
			{
				command: 'svgo --disable removeViewBox -f templates/candy/images && svgo --disable removeViewBox -f templates/default/images'
			},
			svgoModules:
			{
				command: 'svgo --disable removeViewBox -f modules/multi_language/images'
			},
			addUpstream:
			{
				command: 'git remote add upstream git://github.com/redaxmedia/redaxscript.git'
			},
			pullUpstream:
			{
				command: 'git pull upstream master'
			},
			removeUpstream:
			{
				command: 'git remote remove upstream'
			},
			options:
			{
				stdout: true
			}
		},
		copy:
		{
			ruleset:
			{
				files:
				[
					{
						src: ['ruleset.xml'],
						dest: 'vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/Redaxscript/'
					}
				]
			}
		},
		img:
		{
			modules:
			{
				src: ['modules/*/images/*']
			},
			templates:
			{
				src: ['templates/*/images/*']
			}
		},
		smushit:
		{
			modules:
			{
				src: ['<%=img.modules.src%>']
			},
			templates:
			{
				src: ['<%=img.templates.src%>']
			}
		}
	});

	/* load tasks */

	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-htmlhint');
	grunt.loadNpmTasks('grunt-img');
	grunt.loadNpmTasks('grunt-jsonlint');
	grunt.loadNpmTasks('grunt-lineending');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-smushit');

	/* register tasks */

	grunt.registerTask('default', ['jshint', 'jsonlint', 'csslint', 'htmlhint', 'phplint']);
	grunt.registerTask('phplint', ['copy:ruleset', 'phpcs']);
	grunt.registerTask('toc', ['shell:tocBase', 'shell:tocModules', 'shell:tocTemplates']);
	grunt.registerTask('eol', ['lineending']);
	grunt.registerTask('svgo', ['shell:svgoTemplates', 'shell:svgoModules']);
	grunt.registerTask('sync', ['shell:addUpstream', 'shell:pullUpstream', 'shell:removeUpstream']);
	grunt.registerTask('optimize', ['toc', 'eol', 'img', 'smushit', 'svgo']);
};
