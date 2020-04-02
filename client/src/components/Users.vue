<template>
    <div class="users">
        <h1>Users list</h1>

<!-- New Table approach, much easier to edit -->

<table id="users" class="table table-small table-users">
  <thead class="table-head-users">
    <tr class="table-row-users">
      <th>Email</th>
      <th>Name</th>
      <th>Surname</th>
      <th>Operations</th>
    </tr>
  </thead>
  <tbody class="table-body-users">
    <tr v-for="user in users"  v-bind:key="user.id" class="table-row-users">
      <td>{{user.email}}</td>
      <td>{{user.first_name}}</td>
      <td>{{user.last_name}}</td>
      <td><a href="#" class="btn btn-danger"><img class="table-btn" src="https://img.icons8.com/wired/64/000000/trash.png"/></a>
        <a href="#" class="btn btn-primary"><img class="table-btn" src="https://img.icons8.com/pastel-glyph/64/000000/edit.png"/></a></td>
    </tr>
  </tbody>
</table>


 <!--       
    Old table, too hard to edit
    <b-table striped small hover :fields="fields" :items="users"></b-table> 
-->

        <div>
            <b-button class="btn btn-info" id="show-modal" @click="showModal()">Add user</b-button>
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
h1{
    text-align: left;
}
.table-users {
    max-width: 50vw;
    text-align: left;
}

.table-row-users {

}

.table-head-users {
    text-transform: uppercase;
}

.table-head-users th {
    border-right: 1px solid #7D82A8;
}

.table-head-users th:last-child {
  border-right: none;
}

.table-body-users td {
  border-right: 1px solid #7D82A8;
}
.table-body-users td:last-child {
  border-right: none;
  padding:5px;
} 

.table-btn{
    width:20px;
    height:20px;
}
#show-modal {
    left: 100px;
}

</style>