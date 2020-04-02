<template>

    <v-footer
            dark
            padless
            class="footer"
    >
        <v-card
                class="flex"
                flat
                tile
        >
            <v-card-text class="py-2 white--text text-center">
                Hello {{user_name}} {{ date }} — <strong>BursztynCRM</strong> v {{ version }}
            </v-card-text>
        </v-card>
    </v-footer>
</template>

<script>

    import {Settings} from "../_services/settings";
    import {loginService} from "../_services/login.service";

    var moment = require('moment');
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
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 1rem;
        text-align: center;
    }
</style>