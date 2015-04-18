module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['public/js/main.js', 'public/js/search.js'],
        tasks: ['compile'],
        options: {
          spawn: false
        }
      }
    },
    uglify: {
      main: {
        src: 'public/js/main.js',
        dest: 'public/js/main.min.js'
      },
      search: {
        src: 'public/js/search.js',
        dest: 'public/js/search.min.js'
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('minify', ['uglify']);
  grunt.registerTask('compile', ['uglify']);

};
