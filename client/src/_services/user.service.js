export const userService = {
    saveUser
};


function saveUser(data) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    return axios.post('http://localhost:8000/users/create', data, {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {

            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = 'Connection with server problem!';
                return Promise.reject(errorMessage);
            }

        });
}