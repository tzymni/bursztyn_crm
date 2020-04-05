<template>
        <v-card class="calendar">
            <v-app id="dayspan" v-cloak>
                <ds-calendar-app :calendar="calendar"></ds-calendar-app>
            </v-app>
        </v-card>
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

</style>

