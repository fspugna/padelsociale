module.exports = function (grunt) {
	require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);
  
	// Imposta la url di riscrittura degli asset
	var url = '';
	
	var settings = grunt.file.readJSON('settings.json');


	// Configuration
	var config = {
		domain: settings.settings.domain,
		urlCore: 'node_modules/rogiostrap/',
		dev: settings.settings.test,
		urlDist: settings.settings.buildFolder,
		urlCustomLibs: 'js/libs',
		urlComponents: 'js/components',
		dist: settings.settings.buildFolder,
	};

	grunt.initConfig({
	  config: config,
	  pkg: grunt.file.readJSON('package.json'),
	  notify_hooks: {
		options: {
		  enabled: true,
		  success: true,
		  duration: 3
		}
	  },
	  copy: {
		assets: {
		  files: [
			{
			  expand: true,
			  cwd: 'node_modules/rogiostrap.amatoripadel/resources/',
			  src: ['**/*.ttf','**/*.woff','**/*.png','**/*.jpg','**/*.svg','**/*.js','**/*.css',],
			  dest: './resources/'
			}
		  ]
		}
	  },
	  'string-replace': {
		
		build_prod_HTML: {
			files: [
				{
					expand: true,
					cwd: './resources',
					dest: './resources',
					src: [
						'**/*.twig','**/*.js','**/*.css',
					],
				},
			],
			options: {
				replacements: [
					{
						pattern: /@css_extension/g,
						replacement: '',
					},{
						pattern: /@@urlmain/g,
						replacement: '<%= config.urlDist %>/js',
					},{
						pattern: /@@components/g,
						replacement: '<%= config.urlDist %>/<%= config.urlComponents %>',
					},{
						pattern: /@@url/g,
						replacement: '<%= config.urlDist %>',
					},
					{
						pattern: /\/dist\/assets\//g,
						replacement: '<%= config.urlDist %>',
					},
					{
						pattern: /\.\.\/partials/g,
						replacement: 'components\/partials',
					}
				],
			},
		},
	}
	});
	grunt.loadNpmTasks('grunt-string-replace');
	grunt.registerTask('production', 'Copia gli asset nella directory del tema e riscrive i path', [
	  'copy:assets',
	  'string-replace:build_prod_HTML'
	]);
  };
  