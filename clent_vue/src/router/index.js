import Vue from 'vue'
import Router from 'vue-router'
import Users from "../components/Users.vue";
import Calendar from "../components/Calendar";
import Cottages from "../components/Cottages";

Vue.use(Router)

export default new Router({
    routes: [

        {
            path: '/',
            component: Calendar
        },
        {
            path: '/users',
            component: Users
        },
        {
            path: '/cottages',
            component: Cottages
        },
    ]
})