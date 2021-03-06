<template>
  <div class="users">
    <h1>
      <font-awesome-icon icon="user" />
      || {{ header }}
    </h1>
    <div class="container">
      <div>
        <b-button class="btn btn-info" id="show-modal" @click="showModal()"
          >Nowy użytkownik</b-button
        >
        <b-modal
          @hide="setUsers()"
          id="user-form-modal"
          title="User form"
          hide-footer
        >
          <UserForm :editId="$data.editId" v-on:childToParent="showModal" />
        </b-modal>
      </div>

      <div class="table-wrap">
        <b-table
          id="users"
          :per-page="perPage"
          :current-page="currentPage"
          small
          class="table-users"
          :fields="fields"
          :items="users"
          thead-class="thead-dark text-uppercase"
        >
          <template v-slot:head(id)="data">
            <p class="hide">{{ data.field.id }}</p>
          </template>
          <template v-slot:cell(id)="data">
            <p class="hide">{{ data.item.id }}</p>
            <p class="text-center">
              <font-awesome-icon class="icon" icon="user" />
            </p>
          </template>
          <template v-slot:head(is_active)="data">
            <p class="hide">{{ data.field.is_active }}</p>
            <p>Opcje</p>
          </template>
          <template v-slot:cell(is_active)="data">
            <p class="hide">{{ data.item.is_active }}</p>
            <a @click="deleteUser(data.item.id)" class="btn btn-danger">
              <font-awesome-icon icon="trash-alt" />
            </a>
            <a @click="showModal(data.item.id)" class="btn btn-primary">
              <font-awesome-icon icon="edit" />
            </a>
          </template>
        </b-table>
        <b-pagination
          v-model="currentPage"
          :total-rows="rows"
          :per-page="perPage"
          aria-controls="users"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import UserForm from "./UserForm";
import { userService } from "../_services/user.service";

export default {
  name: "Users",
  components: { UserForm },
  data: function() {
    return {
      header: "Użytkownicy",
      users: [],
      perPage: 10,
      currentPage: 1,
      fields: [],
      editId: null,
    };
  },
  mounted() {
    this.setUsers();
  },
  methods: {
    showModal(id = null) {
      this.editId = id;
      this.$bvModal.show("user-form-modal");
    },

    setUsers() {
      var self = this;
      userService
        .getUsers()
        .then(function(response) {
          self.users = response;
        })
        .catch(function(error) {
          if (error) {
            self.errorNotify = error;
            self.loading = false;
          }
        });
    },
    deleteUser(id) {
      this.$confirm(
        "Czy na pewno chcesz usunąć tego użytkownika?",
        "Usuń",
        "Błąd"
      ).then(() => {
        var self = this;
        userService
          .deleteUser(id)
          .then(function() {
            self.setUsers();
          })
          .catch(function(error) {
            if (error) {
              self.errorNotify = error;
              self.loading = false;
            }
          });
      });
    },
  },
  computed: {
    rows() {
      return this.users.length;
    },
  },
};
export { UserForm };
</script>
<style scoped>
.users {
  width: 100%;
  height: 100%;
  max-width: 900px;
}

.table-wrap {
  margin-top: 1rem;
}

.table-users {
  width: 100%;
  text-align: left;
}

.table-btn {
  width: 15px;
  height: 15px;
}

.hide {
  width: 0px;
  height: 0px;
  padding: 0;
  margin: 0;
  display: none;
}

.icon {
  width: 20px;
  height: 20px;
  padding: 0px;
  margin: 0 auto;
}
</style>
