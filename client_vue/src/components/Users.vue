<template>
    <div class="users">
        <h1> Lista użytkowników</h1>
        <br/>
        <div>
            <b-button v-b-modal.modal-1>Dodaj użytkownika</b-button>
            <b-modal id="modal-1" title="BootstrapVue">
                <p class="my-4">Dodawanie usera!</p>
            </b-modal>
        </div>

        <b-table hover :items="users"></b-table>
    </div>

</template>

<b-modal id="modal-1" title="BootstrapVue">
    <p class="my-4">Hello from modal!</p>
</b-modal>
<script>

    import {loginService} from "../_services/login.service";

    export default {
        name: "Users",
        data: function () {
            return {
                users: []
            }
        },
        mounted() {
            this.setUsers();
        },
        methods: {
            setUsers() {
                var self = this;
                loginService.getUsers().then(function (response) {
                        self.users = response;
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                            self.loading = false;
                        }
                    });
            }
        },
    }
</script>