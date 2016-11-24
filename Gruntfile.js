module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['public/js/search.js', 'sass/main.scss'],
        tasks: ['compile'],
        options: {
          spawn: false
        }
      }
    },
    uglify: {
      search: {
        src: 'public/js/search.js',
        dest: 'public/js/search.min.js'
      }
    },
    compass: {
      dist: {
        options: {
          config: 'config.rb'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');

  grunt.registerTask('minify', ['uglify']);
  grunt.registerTask('compile', ['uglify', 'compass']);

};
