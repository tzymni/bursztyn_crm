<template>
  <v-footer
      dark
      flex
      sticky
      class="p-3 text-center footer"

  >
    <v-card-text padless class="text-left footer-div">

      Witaj {{ user_name }}
    </v-card-text>

    <v-card-text padless class="footer-div">
      <strong>BursztynCRM</strong> v {{ version }}
    </v-card-text>
    <v-card-text padless class="text-right footer-div">
      {{ date }}
    </v-card-text>
  </v-footer>
</template>

<script>

import {Settings} from "../_services/settings";
import {loginService} from "../_services/login.service";

const moment = require('moment');
export default {
  data() {
    return {
      date: moment().format('DD-MM-YYYY H:mm'),
      version: Settings.getSystemVersion(),
      user_name: ''
    }
  },
  created() {
    this.getUserLogin();
    setInterval(() => {
      this.date = moment().format('DD-MM-YYYY H:mm');
      this.getUserLogin();
    }, 60000)

  },
  methods: {

    getUserLogin() {
      let user = Settings.getUserFromSession();
      if (user && user.first_name != '')
        this.user_name = user.first_name;
      else if (user && user.email != '') {
        this.user_name = user.email;
      } else {
        loginService.logout();
        location.reload();
      }

    }

  }

}
</script>

<style>
.footer {
  position: sticky;
  width: 100%;
  min-height: 47px !important;
  overflow: hidden;
  z-index: 666;

}

.footer-div {
  margin: 0;
  padding: 0;
  border-right: 1px solid #fff;

}

.footer-div:last-child {
  border-right: none;
}

p {
  display: inline;
  width: 100%;
}
</style>