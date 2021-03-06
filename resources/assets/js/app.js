/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('Example', require('./components/Example.vue'));
Vue.component('toodogd', require('./components/ToodoGD/ToodoGD.vue'));

import ElementUI from 'element-ui';
import Router from 'vue-router';
//import 'element-ui/lib/theme-default/index.css';

import router from './router/index';


window.Vue.use(ElementUI);
window.Vue.use(Router);



const app = new Vue({
    el: '#app',
    router,
});
