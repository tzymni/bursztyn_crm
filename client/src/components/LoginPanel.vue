<template>
    <div>
        <h2>Login</h2>
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="text" v-model="username" name="username" class="form-control"
                       :class="{ 'is-invalid': submitted && !username }"/>
                <div v-show="submitted && !username" class="invalid-feedback">Username is required</div>
            </div>
            <div class="form-group">
                <label htmlFor="password">Hasło</label>
                <input type="password" v-model="password" name="password" class="form-control"
                       :class="{ 'is-invalid': submitted && !password }"/>
                <div v-show="submitted && !password" class="invalid-feedback">Password is required</div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" :disabled="loading">Login</button>
                <img v-show="loading"
                     src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
            </div>
            <div v-if="errorNotify" class="alert alert-danger">{{errorNotify}}</div>
        </form>
    </div>
</template>

<script>

    import {loginService} from "../_services/login.service";
    import {router} from "../router";

    export default {
        data() {
            return {
                username: "",
                password: "",
                submitted: false,
                loading: false,
                returnUrl: "",
                errorNotify: ""
            };
        },
        created() {
            // reset login status
            loginService.logout();

            // get return url from route parameters or default to '/'
            this.returnUrl = this.$route.query.returnUrl || "/";
        },
        name: "LoginPanel",
        methods: {
            handleSubmit() {
                this.submitted = true;
                const {username, password} = this;

                // stop here if form is invalid
                if (!(username && password)) {
                    return;
                }

                this.loading = true;
                var self = this;

                loginService.login(username, password).then(function () {
                        router.push('/');
                    }
                )
                    .catch(function (error) {
                        if (error) {
                            self.errorNotify = error;
                            self.loading = false;
                        }
                    });
            }
        }
    }
</script>