<template>
    <div class="users">
        <h1>Users list</h1>
        <br/>
        <div>
            <b-button id="show-modal" @click="showModal()">Add user</b-button>
            <b-modal id="user-form-modal" title="User form" hide-footer>
                <UserForm />
            </b-modal>
        </div>
        <b-table hover :items="users"></b-table>
    </div>

</template>

<script>
    import UserForm from "./UserForm";
    import {loginService} from "../_services/login.service";

    export default {
        name: "Users",
        components: {UserForm},
        data: function () {
            return {
                users: []
            }
        },
        mounted() {
            this.setUsers();

        },
        methods: {
            showModal() {
                this.$bvModal.show("user-form-modal");
            },
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