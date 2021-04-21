import Vue from "vue";
import Router from "vue-router";
import Users from "../components/Users.vue";
import Calendar from "../components/Calendar";
import Cottages from "../components/Cottages";
import Reservations from "../components/Reservations";
import LoginPanel from "../components/LoginPanel";
import Logout from "../components/Logout";
import CleaningModule from "@/components/CleaningModule";

import TimelineCalendar from "@/components/TimelineCalendar";
Vue.use(Router);

export const router = new Router({
  routes: [
    {
      path: "/",
      component: Calendar,
      meta: {
        title: "Bursztyn - Kalendarz",
      },
    },
    {
      path: "/users",
      component: Users,
      meta: {
        title: "Bursztyn - Użytkownicy",
      },
    },
    {
      path: "/cottages",
      component: Cottages,
      meta: {
        title: "Bursztyn - Domki",
      },
    },
    {
      path: "/timeline-calendar",
      component: TimelineCalendar,
      meta: {
        title: "Bursztyn - Kalendarz liniowy",
      },
    },
    {
      path: "/reservations",
      component: Reservations,
      meta: {
        title: "Bursztyn - Rezerwacje",
      },
    },
    {
      path: "/cleaning-module",
      component: CleaningModule,
      meta: {
        title: "Bursztyn - Nadchodzące zmiany",
      },
    },
    {
      path: "/login",
      component: LoginPanel,
      meta: {
        title: "Bursztyn - Panel logowania",
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
  const publicPages = ["/login"]
  const authRequired = !publicPages.includes(to.path)
  const loggedIn = sessionStorage.getItem("token")
  document.title = to.meta.title

  if (authRequired && !loggedIn) {
    return next({
      path: "/login",
    });
  }

  next()
});
