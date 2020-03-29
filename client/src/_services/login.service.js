export const loginService = {
    login,
    logout,
    getUsers
};

function login(username, password) {

    const axios = require('axios');

    const requestOptions = {
        headers:
            {
                "Content-Type": "application/json",
                "Authorization": 'Basic ' + btoa(username + ":" + password)
            }
    };

    return axios.post('http://localhost:8000/api/authenticate', {}, requestOptions)
        .then(function (response) {
            if (response.data.token) {
                let token = response.data.token;
                sessionStorage.setItem("token", token);
            }
            return response;
        })
        .catch(function (error) {

            if (error.response) {
                const errorMessage = error.response.data.error.message;
                return Promise.reject(errorMessage);
            } else {
                const errorMessage = 'Connection with server problem!';
                return Promise.reject(errorMessage);
            }

        });
}


function logout() {
    // remove user from local storage to log user out
    sessionStorage.removeItem("token");
    // location.reload();
}

function getUsers() {

    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    return axios.get('http://localhost:8000/api/users', { headers: { Authorization: AuthStr } })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {

            if (error.response) {
                const errorMessage = error.response.data.error.message;
                return Promise.reject(errorMessage);
            } else {
                const errorMessage = 'Connection with server problem!';
                return Promise.reject(errorMessage);
            }

        });

}


