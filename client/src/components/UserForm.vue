<template>
  <div>
    <form @submit.prevent="handleSubmit">

      <div class="form-group">
        <label for="email">Adres Email</label>
        <input :disabled="disabledEmailField == true" type="text" v-model="email" name="email"
               class="form-control"
               :class="{ 'is-invalid': submitted && !email }"/>
        <div v-show="submitted && !email" class="invalid-feedback">Podaj adres email.</div>
      </div>
      <div class="form-group">
        <label htmlFor="password">Hasło </label>
        <label class="switch">
          <input type="checkbox" v-on:click="showPassword()">
          <i class="far fa-eye-slash eye-show"></i>
          <i class="far fa-eye eye"></i>
        </label>
        <input id="password" type="password" v-model="password" name="password" class="form-control"
               :class="{ 'is-invalid': submitted && !password }"/>
        <div v-show="submitted && !password" class="invalid-feedback">Podaj hasło</div>
      </div>
      <div class="form-group">
        <label htmlFor="repeat_password">Potwierdź hasło</label>
        <input id="repeat_password" type="password" v-model="repeat_password" name="repeat_password"
               class="form-control"
               :class="{ 'is-invalid': submitted && repeat_password!=password }"/>
        <div v-show="submitted && repeat_password!=password" class="invalid-feedback">Potwierdzone hasło jest
          nieprawidłowe
        </div>
      </div>
      <div class="form-group">
        <label htmlFor="first_name">Imie</label>
        <input type="first_name" v-model="first_name" name="first_name" class="form-control"
               :class="{ 'is-invalid': submitted && !first_name }"/>

      </div>
      <div class="form-group">
        <label htmlFor="last_name">Nazwisko</label>
        <input type="last_name" v-model="last_name" name="last_name" class="form-control"
               :class="{ 'is-invalid': submitted && !last_name }"/>
      </div>
      <div class="form-group">
        <label htmlFor="days_before_notification">Wyślij maila z informacją o rezerwacji X dni przed (0 nie
          wysyłaj)</label>
        <input type="number" min="0" max="14" v-model="days_before_notification" name="days_before_notification"
               class="form-control"
               :class="{ 'is-invalid': submitted && !days_before_notification }"/>
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
import {userService} from "../_services/user.service";

export default {
  name: "UserForm",
  data() {
    return {
      childMessage: '',
      id: null,
      email: "",
      password: "",
      repeat_password: "",
      first_name: "",
      last_name: "",
      days_before_notification: 0,
      submitted: false,
      returnUrl: "",
      errorNotify: "",
      loading: false,
      disabledEmailField: false,
      reg_p: /^[A-Za-z]\w{6,15}$/,
      reg: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    };
  },
  mounted() {
    if (typeof this.editId != 'undefined' && this.editId != null) {
      this.getUserById(this.editId);
    }
  },
  props: {
    editId: Number,
  },
  methods: {
    handleSubmit() {
      this.submitted = true
      let id = this.id
      const {email, password, repeat_password, first_name, last_name, days_before_notification} = this

      // stop here if form is invalid
      if (!(email && password) && !id) {
        return
      } else if (!this.reg.test(email) && !id) {
        this.errorNotify = "Podaj prawidłowy email."
        return
      } else if (!this.reg_p.test(password) && !id) {
        this.errorNotify = "Hasło musi mieć minimum 6 znaków."
        return
      } else if (password != repeat_password && !id) {
        this.errorNotify = "Hasła muszą być takie same."
        return
      }
      const data = {email, password, repeat_password, first_name, last_name, id, days_before_notification}
      const self = this;

      userService.saveUser(data).then(function () {
            self.$bvModal.hide("user-form-modal")
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error
            }
          });
    },
    getUserById(id) {

      const self = this
      userService.getUser(id).then(function (data) {

            self.first_name = data.first_name
            self.last_name = data.last_name
            self.email = data.email
            self.id = data.id
            self.days_before_notification = data.days_before_notification
            self.disabledEmailField = true
          }
      )
          .catch(function (error) {
            if (error) {
              self.errorNotify = error
            }
          });

    },
    showPassword() {
      const passwordField = document.getElementById("password");
      const repeatPasswordField = document.getElementById("repeat_password");
      if (passwordField.type === "password") {
        passwordField.type = "text"
        repeatPasswordField.type = "text"
      } else {
        passwordField.type = "password";
        repeatPasswordField.type = "password";
      }
    },
  },
}
</script>

<style scoped>
/* clear toggle */
.switch {
  position: absolute;
  margin-left: 10px;
  display: inline-block;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The eye */
.eye {
  visibility: hidden;
}

input:checked ~ .eye {
  visibility: visible;
  color: black;
}

input:checked + .eye-show {
  visibility: hidden;
  width: 0;
  height: 0;
}

input:hover + .eye-show, input:hover ~ .eye {
  color: #2196F3;
}
</style>