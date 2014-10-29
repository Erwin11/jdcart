/*
 * jQuery File Upload Gruntfile
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*global module */

module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        concat: {//文件合并
            dist: {
                src: ['public/assets/plugin/jQueryFileUpload/js/vendor/jquery.ui.widget.js',
                      'public/assets/plugin/jQueryFileUpload/js/cdn/load-image.all.min.js',
                      'public/assets/plugin/jQueryFileUpload/js/jquery.fileupload.js',
                      'public/assets/plugin/jQueryFileUpload/js/jquery.fileupload-process.js',
                      'public/assets/plugin/jQueryFileUpload/js/jquery.fileupload-image.js'],
                dest: 'public/assets/plugin/jQueryFileUpload/dist/fileUpload.js'
            }
        },
        uglify: {//js文件压缩
            dist: {
                files: [{
                    expand: true,
                    cwd: 'public/assets/plugin/jQueryFileUpload/dist',
                    src: '*.js',
                    dest: 'public/assets/plugin/jQueryFileUpload/dist',
                    ext: '.min.js'
                }]
            }
        },
        watch: {
            scripts: {
                files: ['public/assets/*.js'],
            },
            livereload: {
                options: {
                  livereload: true
                },
                files: ['public/assets/**/*', 'app/views/**/*.php']
            }
        }
    });

    //load
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    //task
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('distjs', ['concat','uglify']);

};
