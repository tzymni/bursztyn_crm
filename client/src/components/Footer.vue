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
                {{ date }} — <strong>BursztynCRM</strong>
            </v-card-text>
        </v-card>
    </v-footer>
</template>

<script>
    var moment = require('moment');
    export default {
        data () {
            return {
                date: moment().format('DD-MM-YYYY H:mm')
            }
        },
        created() {

            setInterval(() => {

                this.date =  moment().format('DD-MM-YYYY H:mm');
            }, 1000)
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