export const userService = {
    saveUser,
    getUser
};

function saveUser(data) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    if (data.id > 0) {
        return updateUser(data, axios, AuthStr);
    } else {
        return createUser(data, axios, AuthStr);
    }


}

function updateUser(data, axios, AuthStr) {
    return axios.put('http://localhost:8000/api/user/' + data.id, data, {headers: {Authorization: AuthStr}})
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

function createUser(data, axios, AuthStr) {
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


function getUser(id) {

    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    return axios.get('http://localhost:8000/api/user/' + id, {headers: {Authorization: AuthStr}})
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
