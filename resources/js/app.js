require('./bootstrap');

import Vue from 'vue';
import VueMq from 'vue-mq';
import axios from 'axios';
import Vuetify from 'vuetify';
import {store} from '@/js/store/store.js';
import VueRouter from 'vue-router'
import Routes from '@/js/routes.js';
import App from '@/js/views/App.vue';
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import VueSweetalert2 from 'vue-sweetalert2';


//moment js
Vue.use(require('vue-moment'));
//axios
window.axios=axios;
//Sweet alert
Vue.use(VueSweetalert2);
//Vuetify
Vue.use(Vuetify,{iconfont:'md'});
const vuetifyOptions = { }
//vueRouter
Vue.use(VueRouter)
//mobile breakpoints
Vue.use(VueMq, {
    breakpoints: {
        mobile: 900,
        desktop: Infinity,
    }
})
const app = new Vue({
    mode: 'production',

    el:'#app',
    store : store,
    router : Routes,
    render : h => h(App),
    vuetify: new Vuetify(vuetifyOptions),


});

export default app;

