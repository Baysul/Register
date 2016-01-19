module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		jshint: {
			files: ['Gruntfile.js', 'js/<%= pkg.name %>.js'],
			options: {
				globals: {
					jQuery: true
				}
			}
		},
		watch: {
			files: ['<%= jshint.files %>'],
			tasks: ['jshint']
		},
		concat: {
			options: {
				separator: ';'
			},
			dist: {
				src: ['src/js/*.js'],
				dest: 'js/<%= pkg.name %>.js'
			}			
		},
		concat_css: {
			options: {},
			all: {
				src: ["src/css/*.css"],
				dest: "css/<%= pkg.name %>.css"
			},
		},
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
			},
			build: {
				src: 'js/<%= pkg.name %>.js',
				dest: 'js/<%= pkg.name %>.min.js'
			}
		},
		cssmin: {
			target: {
				files: {
					'css/<%= pkg.name %>.min.css': ['css/<%= pkg.name %>.css']
				}
			}
		} 
	});

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-concat-css');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.registerTask('default', ['jshint', 'concat', 'concat_css', 'uglify', 'cssmin']);

};