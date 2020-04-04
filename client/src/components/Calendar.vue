<template>
    <div class="container">
        <div class="calendar">
            <v-app id="dayspan" v-cloak>
                <ds-calendar-app :calendar="calendar"></ds-calendar-app>
            </v-app>
        </div>
    </div>
</template>

<script>
    import {Calendar} from 'dayspan';

    export default {
        name: 'app',
        data: () => ({
            calendar: Calendar.months()
        })
    }
</script>

<style>
    body, html, #app, #dayspan {
        font-family: Roboto, sans-serif;
        width: 100%;
    }
</style>

