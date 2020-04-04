<template>

    <v-footer
            dark
            padless
            flex
            sticky
            class="footer"
    >
        <v-card
                class="flex"
                flat
                tile
        >
        <v-card-text class="py-2 text-center" flex>
            <div class="footer-div">
                Hello {{user_name}}
            </div>
            <div class="footer-div">
                <strong>BursztynCRM</strong> v {{ version }}
            </div>
            <div class="footer-div">
                {{ date }}
            </div></v-card-text>
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
position: sticky; 
    left: 0 ; right: 0; bottom: 0; 
        width: 100%;
        
    }

    .footer-div {
        flex: 33.3%;
        display: inline-block;
        border-right: 1px solid #fff;
        padding: 0 7%;
    }

    .footer-div:last-child {
        border-right: none;
    }

    p {
        display: inline;
        width: 100%;
    }
</style>