import Vue from "vue";
import Router from "vue-router";
import Users from "../components/Users.vue";
import Calendar from "../components/Calendar";
import Cottages from "../components/Cottages";
import Reservations from "../components/Reservations";
import LoginPanel from "../components/LoginPanel";
import Logout from "../components/Logout";

Vue.use(Router);

export const router = new Router({
  routes: [
    {
      path: "/",
      component: Calendar,
      meta: {
        title: "Bursztyn - Calendar",
      },
    },
    {
      path: "/users",
      component: Users,
      meta: {
        title: "Bursztyn - Users",
      },
    },
    {
      path: "/cottages",
      component: Cottages,
      meta: {
        title: "Bursztyn - Cottages",
      },
    },
    {
      path: "/reservations",
      component: Reservations,
      meta: {
        title: "Bursztyn - Manage reservations",
      },
    },
    {
      path: "/login",
      component: LoginPanel,
      meta: {
        title: "Bursztyn - Login Panel",
      },
    },
    {
      path: "/logout",
      component: Logout,
      meta: {
        title: "Bursztyn - Bye bye",
      },
    },
  ],
});

router.beforeEach((to, from, next) => {
  // redirect to login page if not logged in and trying to access a restricted page
  const publicPages = ["/login"];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = sessionStorage.getItem("token");
  document.title = to.meta.title;

  if (authRequired && !loggedIn) {
    return next({
      path: "/login",
    });
  }

  next();
});
