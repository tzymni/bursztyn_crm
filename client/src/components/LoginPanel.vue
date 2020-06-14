<template>
    <div class="wrap-container">
        <b-card bg-variant="light" 
                text-variant="dark" 
                border-variant="dark" 
                header="BursztynCRM"
                header-text-variant="white"
                header-bg-variant="dark"
                header-class="p-4 card_header"
                class="text-center">
        <form class="p-5 pt-0 text-left" @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="text" v-model="username" name="username" class="form-login"
                       :class="{ 'is-invalid': submitted && !username }"/>
                <div v-show="submitted && !username" class="invalid-feedback">Email address is required</div>
            </div>
            <div class="form-group">
                <label htmlFor="password">Password</label>
                <input type="password" v-model="password" name="password" class="form-login"
                       :class="{ 'is-invalid': submitted && !password }"/>
                <div v-show="submitted && !password" class="invalid-feedback">Password is required</div>
            </div>
            <div class="text-right form-group small">
<!--                <b-link class="font-weight-light small" href="#foo">Forgotten Password?</b-link>-->
            </div>
            <div class="form-group text-center">
                <button class="btn btn-outline-dark rounded-pill py-2 mt-2" :disabled="loading">Sign in</button>
                <img v-show="loading"
                     src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
            </div>
            <div v-if="errorNotify" class="alert alert-danger">{{errorNotify}}</div>
        </form>

</b-card>
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
                        self.$parent.toggleComponent();
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
<style scooped>

.wrap-container {
    height: 100%;
    min-height:100vh;
    margin: 0;
    padding: 15vh;
    font-size: 1.5rem;
    font-weight: 300;
    line-height: 1.5;
    color: ##343a40;
    background-color: #ededee;
}


.card {
    margin: 0 auto;
    max-width: 30rem;
    box-shadow: 0px 5px 5px -3px rgba(0, 0, 0, 0.2), 0px 8px 10px 1px rgba(0, 0, 0, 0.14), 0px 3px 14px 2px rgba(0, 0, 0, 0.12);
    border-radius: 24px 4px !important;
    overflow: hidden;
}



label {
    font-weight: 100;
    font-size: 0.9rem;
    margin: 0;
    padding: 0;
}

input.form-login {
    background: transparent;
    border: none;
    border-bottom: 1px solid #343a40;
    width: 100%;
    outline: none;
    padding: 0px 0px 0px 0px;
    font-style: italic;
}

input.form-login:focus {
    border-color: #4B545C;
    transition: border-color 0.6s linear;
}

input.is-invalid {
    border-color: red;
}

</style>