import Vue from 'vue'
import App from './App.vue'
import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import {router} from './router';
import VueRouter from 'vue-router'
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vuetify from 'vuetify'
import {library} from '@fortawesome/fontawesome-svg-core'
import {faCoffee} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import 'vue-good-table/dist/vue-good-table.css'

import VueGoodTablePlugin from 'vue-good-table';

import VueSimpleAlert from "vue-simple-alert";

Vue.use(VueSimpleAlert);

require('@/assets/css/global.css');
Vue.use(VueGoodTablePlugin);
Vue.use(Vuetify);
Vue.use(VueSidebarMenu);
Vue.use(VueRouter);
// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

library.add(faCoffee)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

new Vue({
    el: '#app',
    router,
    components: {
        App,
    },
    template: '<App/>',
    render: h => h(App),
}).$mount('#app')


