<template>
  <div>
    <form @submit.prevent="handleSubmit">

      <div class="form-group">
        <label htmlFor="date_from">Od</label>
        <input type="date" v-model="date_from" name="date_from"
               class="form-control"
               :class="{ 'is-invalid': submitted && !date_from }"/>
        <div v-show="submitted && !date_from" class="invalid-feedback">Podaj date od.</div>
      </div>
      <div class="form-group">
        <label htmlFor="date_to">Do</label>
        <input type="date" v-model="date_to" name="date_to"
               class="form-control"
               :class="{ 'is-invalid': submitted && !date_to }"/>
        <div v-show="submitted && !date_to" class="invalid-feedback">Podaj date do.</div>
      </div>
      <div class="form-group">
        <label htmlFor="user_id">Użytkownik</label>
        <b-form-select
            v-model="user_id"
            :options="options"
            class="form-control"
            :class="{ 'is-invalid': submitted && !user_id }"
        />
        <div v-show="submitted && !user_id" class="invalid-feedback">Użytkownik jest wymagany
        </div>
      </div>
      <div class="form-group">
        <label htmlFor="extra_info">Dodatkowe informacje (np. przyjeżdzam z pieskiem)</label>
        <input type="extra_info" v-model="extra_info" name="extra_info" class="form-control"/>
      </div>
      <div class="form-group">
        <button class="btn btn-primary" :disabled="loading">Zapisz</button>
        <img v-show="loading"
             src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
      </div>
      <div v-if="errorNotify" class="alert alert-danger">{{ errorNotify }}</div>
    </form>
  </div>
</template>

<script>
import {userPresenceService} from "@/_services/user_presence.service";
import {userService} from "@/_services/user.service";

export default {
  name: "UserPresenceForm",
  data() {
    return {
      childMessage: '',
      id: null,
      date_from: "",
      date_to: "",
      user_id: "",
      extra_info: "",
      submitted: false,
      returnUrl: "",
      errorNotify: "",
      loading: false,
      options: []
    };
  },
  mounted() {

    if (typeof this.editId != 'undefined' && this.editId != null) {
      this.getUserPresenceById(this.editId);
    }

    this.getUsers()
  },
  props: {
    editId: Number,
  },
  methods: {
    handleSubmit() {
      this.submitted = true
      let id = this.id
      const {date_from, date_to, user_id, extra_info} = this
      const data = {date_from, date_to, user_id, extra_info, id}
      const self = this;

      // stop here if form is invalid
      if (!(date_from && date_to && user_id) && !id) {
        return;
      }

      userPresenceService.saveUserPresence(data).then(function () {
            self.$bvModal.hide("user-presence-form-modal")
            self.editId = null
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error
        }
      });
    },
    getUsers() {
      const self = this
      userService.getUsers().then(function (response) {
            let list = []
            response.map(function (value) {
              let text = value.first_name + ' ' + value.last_name
              list.push({value: value.id, text: text})
            });
            self.options = list
          }
      ).catch(function (error) {
        if (error) {
          self.errorNotify = error
          self.loading = false
        }
      });
    },

    getUserPresenceById(id) {

      const self = this
      userPresenceService.getUserPresenceEvent(id).then(function (data) {
            self.user_id = data.details.user_id
            self.date_from = data.event.date_from
            self.date_to = data.event.date_to
            self.extra_info = data.details.extra_info
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error
            }
          });
    },
  },
}
</script>

<style scoped>

</style>