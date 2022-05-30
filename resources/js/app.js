require('./bootstrap');

// window.Vue = require('vue').default;

import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);
Vue.component('url-component', require('./components/UrlShortenerComponent.vue').default);

const app = new Vue({
    el: '#app',
});
