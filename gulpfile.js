const { src, dest, parallel } = require('gulp');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const cleanCSS = require('gulp-clean-css');

function css(){
    return src([
        "node_modules/@coreui/coreui/dist/css/coreui.css",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.css",
        "node_modules/toastr/build/toastr.min.css",
        "node_modules/xterm/dist/xterm.css",
        "src/assets/token/styles/token-input.css",
        "src/assets/token/styles/token-input-facebook.css",
        "node_modules/dropzone/dist/min/basic.min.css",
        "node_modules/dropzone/dist/min/dropzone.min.css"


    ])
    .pipe(cleanCSS({}))
    .pipe(concat("external.dist.css"))
    .pipe(dest('src/assets/dist'));
}

function js(){
    return src([
            "node_modules/ace-builds/src-min/ace.js",
            "node_modules/ace-builds/src-min/theme-monokai.js",
            "node_modules/ace-builds/src-min/mode-yaml.js",
            "node_modules/jquery-confirm/dist/jquery-confirm.min.js",
            "node_modules/toastr/build/toastr.min.js",
            "src/assets/token/src/jquery.tokeninput.js",
            "node_modules/dropzone/dist/min/dropzone.min.js"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('external.dist.js'))
        .pipe(dest('src/assets/dist'))
}

function xterm(){
    return src([
            "node_modules/xterm/dist/xterm.js",
            "node_modules/xterm/dist/addons/attach/attach.js"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('xterm.js'))
        .pipe(dest('src/assets/dist'))
}

exports.js = js;
exports.extrnalCss = css;
exports.xterm = xterm;
exports.default = parallel(js, css, xterm);
