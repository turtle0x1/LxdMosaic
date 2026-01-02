import gulp from 'gulp';
import concat from 'gulp-concat';
import minify from 'gulp-minify';
import cleanCSS from 'gulp-clean-css';
import replace from 'gulp-replace';

const { src, dest, parallel } = gulp;


export function css() {
    return src([
        "node_modules/bootstrap/dist/css/bootstrap.min.css",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.css",
        "node_modules/toastr/build/toastr.min.css",
        "node_modules/@xterm/xterm/css/xterm.css",
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

export function js() {
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
        "node_modules/navigo/lib/navigo.min.js"
    ])
    .pipe(minify({ noSource: true }))
    .pipe(concat('external.dist.js'))
    .pipe(dest('src/assets/dist'));
}

export function xterm() {
    return src([
        "node_modules/@xterm/xterm/lib/xterm.js",
        "node_modules/@xterm/addon-attach/lib/addon-attach.js",
        "node_modules/@xterm/addon-fit/lib/addon-fit.js"
    ])
    .pipe(concat('xterm.js'))
    .pipe(dest('src/assets/dist'));
}

export function fonts() {
    return src([
        "node_modules/jquery-contextmenu/dist/font/context-menu-icons.ttf",
        "node_modules/jquery-contextmenu/dist/font/context-menu-icons.woff",
        "node_modules/jquery-contextmenu/dist/font/context-menu-icons.woff2"
    ], { encoding: false })
    .pipe(dest('src/assets/dist/font'));
}

export function fontAwesomeFonts() {
    return src("node_modules/@fortawesome/fontawesome-free/webfonts/*", { encoding: false })
        .pipe(dest('src/assets/dist/fontawesome'));
}

export function fontAwesomeCss() {
    return src([
        "node_modules/@fortawesome/fontawesome-free/css/all.min.css"
    ])
        .pipe(replace("../webfonts/", "/assets/dist/fontawesome/"))
        .pipe(minify({ noSource: true }))
        .pipe(concat("external.fontawesome.css"))
        .pipe(dest('src/assets/dist/'));
}

export function preAuthCss() {
    return src([
        "node_modules/bootstrap/dist/css/bootstrap.min.css",
        "node_modules/toastr/build/toastr.min.css",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.css"
    ])
        .pipe(cleanCSS({}))
        .pipe(concat("login.dist.css"))
        .pipe(dest('src/assets/dist'));
}

export function preAuthJs() {
    return src([
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/@popperjs/core/dist/umd/popper.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.min.js",
        "node_modules/toastr/build/toastr.min.js",
        "node_modules/jquery-confirm/dist/jquery-confirm.min.js"
    ])
    .pipe(minify({ noSource: true }))
    .pipe(concat('login.dist.js'))
    .pipe(dest('src/assets/dist'));
}

export function aceYaml() {
    return src([
        "node_modules/ace-builds/src-min/worker-yaml.js"
    ]).pipe(dest("src/assets/dist/"));
}

export default parallel(
    js,
    css,
    xterm,
    fonts,
    fontAwesomeCss,
    preAuthCss,
    preAuthJs,
    fontAwesomeFonts,
    aceYaml
);
