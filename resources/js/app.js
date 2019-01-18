/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./template/sb-admin');
import $ from "jquery";
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import Vuex from 'vuex';

window.Vue = require('vue');
Vue.use(Vuex);
Vue.use(Element)

const store = new Vuex.Store({
    state: {
        count: 0
    },
    mutations: {
        increment (state) {
            state.count++
        }
    }
})

import Drive from './components/user/drive.vue'
import Contact from './components/user/contact.vue'
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    data:{
        loading:true
    },
    methods: {
        sidebarToggle(e) {
            e.preventDefault();
            $("body").toggleClass("sidebar-toggled");
            $(".sidebar").toggleClass("toggled");
        }
    },
    components: {
        Drive,
        Contact
    }
});

