export const userService = {
    saveUser,
    getUser,
    deleteUser,
    getUsers,
};

// TODO make axios, token, AuthStr as global variable

function saveUser(data) {

    if (data.id > 0) {
        return updateUser(data);
    } else {
        return createUser(data);
    }
}

function updateUser(data) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);
    return axios.put('http://localhost:8000/user/' + data.id, data, {headers: {Authorization: AuthStr}})
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

function createUser(data) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);
    return axios.post('http://localhost:8000/user', data, {headers: {Authorization: AuthStr}})
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

function getUsers() {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    return axios.get('http://localhost:8000/user/list', {headers: {Authorization: AuthStr}})
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

function deleteUser(id) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);
    return axios.delete('http://localhost:8000/user/' + id, {headers: {Authorization: AuthStr}})
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
    return axios.get('http://localhost:8000/user/' + id, {headers: {Authorization: AuthStr}})
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
