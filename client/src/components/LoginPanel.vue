<template>
    <div class="wrap-container">
    <div class="container-form-group">
        <h2>Log-in Panel</h2>
        <form @submit.prevent="handleSubmit">
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
            <div class="form-group">
                <button class="btn-login" :disabled="loading">Sign in</button>
                <img v-show="loading"
                     src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA=="/>
            </div>
            <div v-if="errorNotify" class="alert alert-danger">{{errorNotify}}</div>
        </form>
    </div>
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
<style>

@import url('https://fonts.googleapis.com/css2?family=Open+Sans+Condensed&family=Raleway&display=swap');

.wrap-container {
    height: 100vh;
    font-family: 'Open Sans Condensed', sans-serif;
    margin: 0;
    padding: 10vh;
    font-size: 1rem;
    font-weight: 300;
    line-height: 1.5;
    color: #212529;
    background-color: #ededee;
    box-sizing: border-box;
}



.container-form-group {
    margin: 0 auto;
    padding: 4rem;
    width: 20rem;
    color: #ededee;
    background: #292121;
    border-style: outset;
    border-radius: 0.5rem;
    border-color: #ededee;
    border-width: 1px;
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
    border-bottom: 2px dotted #ededee;
    width: 100%;
    outline: none;
    padding: 0px 0px 0px 0px;
    font-style: italic;
}

input.form-login:focus {
    border-color: #DDDDDD;
}

input.is-invalid {
    border-color: red;
}

.btn-login{
    display:inline-block;
    padding:0.5em 3em;
    border-bottom: 2px solid #ededee;
    border-radius: 0.5rem;
    margin: 1rem;
    box-sizing: border-box;
    text-decoration:none;
    text-transform:uppercase;
    color:#ededee;
    text-align:center;
    transition: all 0.15s;
}

.btn-login:hover{
    color:#DDDDDD;
    border-color:#DDDDDD;
}

.btn-login:active{
    color:#BBBBBB;
    border-color:#BBBBBB;
}



</style>