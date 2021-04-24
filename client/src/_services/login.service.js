export const loginService = {
    login,
    logout,
};

import {config} from "@/config";

const axios = require('axios')

/**
 * Ask API for token.
 *
 * @param username
 * @param password
 * @returns {Promise<AxiosResponse<any>>}
 */
function login(username, password) {

    const requestOptions = {
        headers:
            {
                "Content-Type": "application/json",
                "Authorization": 'Basic ' + btoa(username + ":" + password)
            }
    }

    console.log(config.apiURL.path)

    return axios.post('http://localhost:'+ config.apiURL.port + '/api/authenticate', {}, requestOptions)
        .then(function (response) {
            if (response.data.token) {
                let token = response.data.token
                let user = response.data.user
                sessionStorage.setItem("token", token)
                sessionStorage.setItem("user", JSON.stringify(user))
            }
            return response
        })
        .catch(function (error) {

            if (error.response) {
                const errorMessage = error.response.data.error.message
                return Promise.reject(errorMessage)
            } else {
                const errorMessage = 'Connection with server problem!'
                return Promise.reject(errorMessage)
            }
        });
}

/**
 * Remove token from the storage.
 */
function logout() {
    // remove user from local storage to log user out
    sessionStorage.removeItem("token")
}




