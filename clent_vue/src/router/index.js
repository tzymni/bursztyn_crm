import Vue from 'vue'
import Router from 'vue-router'
import Users from "../components/Users.vue";
import Calendar from "../components/Calendar";
import Cottages from "../components/Cottages";
import LoginPanel from "../components/LoginPanel";

Vue.use(Router)

export const router = new Router({
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
        {
            path: '/login',
            component: LoginPanel
        },
    ]
})

router.beforeEach((to, from, next) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = ['/login'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = sessionStorage.getItem('token');

    if (authRequired && !loggedIn) {
        return next({
            path: '/login'
        });
    }

    next();
})
