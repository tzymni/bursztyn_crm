<template>
  <div id="menuPanel" :class="[{ collapsed: collapsed }]">
    <div class="menuPanel">
      <div class="container">
        <router-view />
      </div>
      <sidebar-menu
        :menu="menu"
        :collapsed="collapsed"
        :show-one-child="true"
        @toggle-collapse="onToggleCollapse"
      />
    </div>

    <Footer />
  </div>
</template>

<script>
import Footer from "./Footer";

export default {
  components: { Footer },
  data() {
    return {
      name: "Menu",

      menu: [
        {
          header: true,
          title: "Menu",
          hiddenOnCollapse: true,
        },
        {
          href: "/",
          title: "Kalendarz",
          icon: "fa fa-calendar-alt",
        },
        {
          title: "Zasoby",
          icon: "fa fa-warehouse",
          child: [
            {
              href: "/cottages",
              title: "Domki",
              icon: "fa fa-home",
            },
            {
              href: "/users",
              title: "Użytkownicy",
              icon: "fa fa-user",
            },
          ],
        },
        {
          title: "Zarządzanie",
          icon: "fa fa-tasks",
          child: [
            {
              href: "/reservations",
              title: "Rezerwacje",
              icon: "fa fa-calendar-check",
            },
            {
              href: "/cleaning-module",
              title: "Nadchodzące zmiany",
              icon: "fa fa-broom",
            },
          ],
        },
        {
          href: "/logout",
          title: "Wyloguj",
          icon: "fa fa-sign-out-alt",
          meta: { hideNavigation: true },
        },
      ],
      collapsed: false,
    };
  },
  methods: {
    onToggleCollapse(collapsed) {
      this.collapsed = collapsed;
    },
  },
};
</script>
<style lang="scss">
@import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600");
body,
html {
  margin: 0;
  padding: 0;
}
body {
  font-family: "Source Sans Pro", sans-serif;
  font-size: 18px;
  background-color: #f2f4f7;
  color: #262626;
}
#menuPanel {
  padding-left: 350px;
}
#menuPanel.hide {
  display: none;
}
#menuPanel.collapsed {
  padding-left: 50px;
}
pre {
  font-family: Consolas, monospace;
  color: #000;
  background: #fff;
  border-radius: 2px;
  padding: 15px;
  line-height: 1.5;
  overflow: auto;
}
</style>
