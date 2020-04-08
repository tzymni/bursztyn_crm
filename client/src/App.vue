<template>
    <div id="app">
        <component v-bind:is="component"/>
    </div>

</template>
<script>
    import Menu from './components/Menu.vue'
    import LoginPanel from "./components/LoginPanel";

    require('@/assets/css/global.css');

    export default {
        data() {
            return {
                component: "LoginPanel"
            }
        },
        created() {

            const loggedIn = sessionStorage.getItem('token');
            if (loggedIn) {
                this.component = Menu;
            }
        },
        name: 'app',
        components: {
            Menu,
            LoginPanel
        },
        methods: {
            toggleComponent() {
                if (this.component === Menu) {
                    this.component = LoginPanel;
                } else {
                    this.component = Menu;
                }
            },
        },
        install(Vue) {
            Vue.component('menu', Menu)

        }
    }
    export {Menu}
</script>
<style>
    @import './assets/css/global.css';
</style>
