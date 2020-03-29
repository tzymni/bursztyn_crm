<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" v-model="email" name="email" class="form-control"
                       :class="{ 'is-invalid': submitted && !username }"/>
                <div v-show="submitted && !email" class="invalid-feedback">Email is required</div>
            </div>
            <div class="form-group">
                <label htmlFor="password">Password</label>
                <input type="password" v-model="password" name="password" class="form-control"
                       :class="{ 'is-invalid': submitted && !password }"/>
                <div v-show="submitted && !password" class="invalid-feedback">Password is required</div>
            </div>
            <div class="form-group">
                <label htmlFor="first_name">First name</label>
                <input type="first_name" v-model="first_name" name="first_name" class="form-control"
                       :class="{ 'is-invalid': submitted && !first_name }"/>

            </div>
            <div class="form-group">
                <label htmlFor="last_name">Last name</label>
                <input type="last_name" v-model="last_name" name="last_name" class="form-control"
                       :class="{ 'is-invalid': submitted && !last_name }"/>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" :disabled="loading">Save and close</button>
                <img v-show="loading"
                     src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
            </div>
            <div v-if="errorNotify" class="alert alert-danger">{{errorNotify}}</div>
        </form>
    </div>
</template>

<script>
    // import {loginService} from "../_services/login.service";
    // import {router} from "../router";

    export default {
        name: "UserForm",
        data() {
            return {
                email: "",
                password: "",
                first_name: "",
                last_name: "",
                submitted: false,
                loading: false,
                returnUrl: "",
                errorNotify: ""
            };
        },
        methods: {
            handleSubmit() {
                console.log("TEST");
                // this.$emit("close")
                this.$bvModal.hide("user-form-modal");

                this.submitted = true;
                const {email, password} = this;

                // stop here if form is invalid
                if (!(email && password)) {
                    return;
                }
                //
                // this.loading = true;
                // var self = this;
                //
                // loginService.login(username, password).then(function () {
                //         router.push('/');
                //         self.$parent.toggleComponent();
                //     }
                // )
                //     .catch(function (error) {
                //         if (error) {
                //             self.errorNotify = error;
                //             self.loading = false;
                //         }
                //     });
            }
        }
    }
</script>

<style scoped>

</style>