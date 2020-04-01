<template>
    <div class="users">
        <h1>Users list</h1>

<!-- New Table approach, much easier to edit -->

<table id="users" class="table-users">
  <thead class="table-head-users">
    <tr class="table-row-users">
      <th>Email</th>
      <th>Name</th>
      <th>Surname</th>
      <th>Operatins</th>
    </tr>
  </thead>
  <tbody class="table-body-users">
    <tr v-for="user in users"  v-bind:key="user.id" class="table-row-users">
      <td>{{user.email}}</td>
      <td>{{user.first_name}}</td>
      <td>{{user.last_name}}</td>
      <td><a href="#" class="btn btn-danger table-btn">Dummie Delete</a></td>
    </tr>
  </tbody>
</table>


 <!--       
    Old table, too hard to edit
    <b-table striped small hover :fields="fields" :items="users"></b-table> 
-->

        <div>
            <b-button id="show-modal" @click="showModal()">Add user</b-button>
            <b-modal id="user-form-modal" title="User form" hide-footer>
                <UserForm />
            </b-modal>
        </div>
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
<style scoped>
.table-users {
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
    border: 3px solid #44475C;
}

.table-row-users {

}

.table-head-users {
    text-transform: uppercase;
    text-align: left;
    background: #44475C;
    color: #FFF;
    padding: 8px;
    min-width: 30px;
}

.table-head-users th {
    padding: 1rem;
    border-right: 2px solid #fff;
}

.table-head-users th:last-child {
  border-right: none;
}

.table-body-users td {
  text-align: left;
  padding: 8px;
  border-right: 2px solid #7D82A8;
  border-bottom: 1px solid #7D82A8;
}
table-body-users td:last-child {
  border-right: none;
}



</style>