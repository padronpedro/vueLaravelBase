/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import 'es6-promise/auto'
import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import Vuetify from 'vuetify';
import VueI18n from 'vue-i18n';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import VueTheMask from 'vue-the-mask';
import VueAuth from '@websanova/vue-auth'
import languageBundle from '@kirschbaum-development/laravel-translations-loader!@kirschbaum-development/laravel-translations-loader';

import App from './App.vue';
import auth from './auth'
import router from './router';
// import functions from './helpers/functions';

window.Vue = require('vue');

Vue.use(Vuex);

Vue.router = router;
Vue.use(VueRouter);

Vue.use(VueAxios, axios);
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`;
Vue.use(VueAuth, auth);

Vue.use(Vuetify);
Vue.use(VueI18n);
Vue.use(VueTheMask);
// Vue.use(functions);

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
})

const i18n = new VueI18n({
    locale: 'es',//window.Locale,
    messages: languageBundle,
});

const store = new Vuex.Store({
    state: {
      isLogged: !!localStorage.getItem("token")
    },
    mutations: {
      increment (state) {
        state.count++
      }
    }
});

// Predefined color
Vue.prototype.$colors = {
    red: 'rgba(255, 0, 0, 1)',
    orange: 'rgba(239, 124, 0, 1)',
    blueVolumen: 'rgb(198,225,230)',
    white: 'color:#ffffff !important',
};

Vue.prototype.$showError = function(message, color, app){
    app.snackbar.txtErrorMessage=message;
    app.snackbar.color = color;
    app.snackbar.showErrorMessage=true;
    setTimeout(()=>{
        app.snackbar.showErrorMessage=false;
    },2000);
};

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


App.router = Vue.router;

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(/*{
        theme: {
          dark: true,
        },
      }*/),
    i18n,
    App,
    router,
    render: app => app(App)
});
