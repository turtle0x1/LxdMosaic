const { src, dest, parallel } = require('gulp');
const concat = require('gulp-concat');
const minify = require('gulp-minify');
const cleanCSS = require('gulp-clean-css');
const replace = require("gulp-replace");

function css(){
    return src([
        "node_modules/bootstrap/dist/css/bootstrap.min.css",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.css",
        "node_modules/toastr/build/toastr.min.css",
        "node_modules/xterm/css/xterm.css",
        "src/assets/token/styles/token-input.css",
        "src/assets/token/styles/token-input-facebook.css",
        "node_modules/dropzone/dist/min/basic.min.css",
        "node_modules/dropzone/dist/min/dropzone.min.css",
        "node_modules/jquery-timepicker/jquery.timepicker.css",
        "node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.css",
        "node_modules/chart.js/dist/Chart.min.css"
    ])
    .pipe(cleanCSS({}))
    .pipe(concat("external.dist.css"))
    .pipe(dest('src/assets/dist'));
}

function js(){
    return src([
            "node_modules/jquery/dist/jquery.min.js",
            "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
            "node_modules/moment/min/moment.min.js",
            "node_modules/ace-builds/src-min/ace.js",
            "node_modules/ace-builds/src-min/theme-monokai.js",
            "node_modules/ace-builds/src-min/mode-yaml.js",
            "node_modules/jquery-confirm/dist/jquery-confirm.min.js",
            "node_modules/toastr/build/toastr.min.js",
            "src/assets/token/src/jquery.tokeninput.js",
            "node_modules/dropzone/dist/min/dropzone.min.js",
            "node_modules/jquery-timepicker/jquery.timepicker.js",
            "node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.js",
            "node_modules/jquery-contextmenu/dist/jquery.ui.position.min.js",
            "node_modules/chart.js/dist/Chart.min.js",
            "node_modules/masonry-layout/dist/masonry.pkgd.min.js",
            'node_modules/navigo/lib/navigo.min.js',
            'src/assets/jquery-resizeable/jquery-ui.min.js'
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('external.dist.js'))
        .pipe(dest('src/assets/dist'))
}

function xterm(){
    return src([
            "node_modules/xterm/lib/xterm.js",
            "node_modules/xterm-addon-attach/lib/xterm-addon-attach.js",
            "node_modules/xterm-addon-fit/lib/xterm-addon-fit.js"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('xterm.js'))
        .pipe(dest('src/assets/dist'))
}

function fonts(){
    return src([
            "node_modules/jquery-contextmenu/dist/font/context-menu-icons.ttf",
            "node_modules/jquery-contextmenu/dist/font/context-menu-icons.woff",
            "node_modules/jquery-contextmenu/dist/font/context-menu-icons.woff2"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(dest('src/assets/dist/font'))
}
function fontAwesomeFonts(){
    return src([
            "node_modules/@fortawesome/fontawesome-free/webfonts/*"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(dest('src/assets/dist/fontawesome'))
}

function fontAwesomeCss(){
    return src([
            "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
        ])
        .pipe(replace("../webfonts/", "/assets/dist/fontawesome/"))
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat("external.fontawesome.css"))
        .pipe(dest('src/assets/dist/'))
}

function preAuthCss(){
    return src([
        "node_modules/bootstrap/dist/css/bootstrap.min.css",
        "node_modules/toastr/build/toastr.min.css",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.css"
    ])
    .pipe(cleanCSS({}))
    .pipe(concat("login.dist.css"))
    .pipe(dest('src/assets/dist'));
}

function preAuthJs(){
    return src([
            "node_modules/jquery/dist/jquery.min.js",
            "node_modules/@popperjs/core/dist/umd/popper.min.js",
            "node_modules/bootstrap/dist/js/bootstrap.min.js",
            "node_modules/toastr/build/toastr.min.js",
            "node_modules/jquery-confirm/dist/jquery-confirm.min.js"
        ])
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('login.dist.js'))
        .pipe(dest('src/assets/dist'))
}

exports.js = js;
exports.extrnalCss = css;
exports.xterm = xterm;
exports.xterm = fonts;
exports.fontAwesomeCss = fontAwesomeCss;
exports.preAuthCss = preAuthCss;
exports.preAuthJs = preAuthJs;
exports.preAuthJs = fontAwesomeFonts;
exports.default = parallel(js, css, xterm, fonts, fontAwesomeCss, preAuthCss, preAuthJs, fontAwesomeFonts)
