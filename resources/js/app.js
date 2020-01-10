/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import 'es6-promise/auto'
import axios from 'axios'
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth'
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';
import VueTheMask from 'vue-the-mask';
// import functions from './helpers/functions';
import VueI18n from 'vue-i18n';
import languageBundle from '@kirschbaum-development/laravel-translations-loader!@kirschbaum-development/laravel-translations-loader';

import App from './App.vue';

import router from './router';
import auth from './auth'

window.Vue = require('vue');
Vue.router = router;
Vue.use(VueRouter);

Vue.use(VueAxios, axios);
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`
Vue.use(VueAuth, auth)

Vue.use(Vuetify);
Vue.use(VueI18n);
Vue.use(VueTheMask);
// Vue.use(functions);

Vue.prototype.$colors = {
    red: 'rgba(255, 0, 0, 1)',
    orange: 'rgba(239, 124, 0, 1)',
};

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
  })

const i18n = new VueI18n({
    locale: 'es',//window.Locale,
    messages: languageBundle,
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


export const EventBus = new Vue();

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
