require('angular');
require('angular-cookies');
require('@uirouter/angularjs');
require('angular-sanitize');
require('../../bower_components/angular-bind-html-compile/angular-bind-html-compile');
require('angular-animate');
require('angular-load');
require('angular-loading-bar');
require('../../bower_components/ngCart/dist/ngCart')
require('angular-youtube-embed');
require('@iamadamjowett/angular-click-outside');
require('../../bower_components/angular-i18n/angular-locale_it-it');
//require('../../bower_components/angular-timeago/dist/angular-timeago');
//require('../../bower_components/angular-timeago/src/languages/time-ago-language-it_IT');
window.controller = new ScrollMagic.Controller()
var iro = angular.module('iro', ['ngSanitize','angular-loading-bar', 'angular-click-outside', 'ui.router', 'angularLoad', 'ngAnimate', 'angular-bind-html-compile', 'ngCart', 'ngCookies', 'youtube-embed']);
const vars = vars;
export const speed = 0.5;
require('./models/index');
require('./resources/index');
require('./directives/index');
require('./animations/index');
angular.element(document).ready( () => angular.bootstrap(document, ['iro']));