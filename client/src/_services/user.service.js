export const userService = {
    saveUser,
    getUser,
    deleteUser,
    getUsers,
};

import {Settings} from "@/_services/settings";
import {config} from "@/config";

/**
 * Save user.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function saveUser(data) {

    if (data.id > 0) {
        return updateUser(data);
    } else {
        return createUser(data);
    }
}

/**
 * Update user by id.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function updateUser(data) {
    const axios = require('axios');
    const AuthStr = Settings.generateAuthenticationString()
    return axios.put(config.apiURL.path + config.apiURL.port + '/user/' + data.id, data, {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {

            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = 'Connection with server problem!'
                return Promise.reject(errorMessage)
            }
        });
}

/**
 * Create a new user.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function createUser(data) {
    const axios = require('axios')
    const AuthStr = Settings.generateAuthenticationString()
    return axios.post(config.apiURL.path + config.apiURL.port + '/user', data, {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = 'Connection with server problem!'
                return Promise.reject(errorMessage)
            }
        });
}

/**
 * Get all active users from API.
 *
 * @returns {Promise<AxiosResponse<any>>}
 */
function getUsers() {
    const axios = require('axios')
    const AuthStr = Settings.generateAuthenticationString()

    return axios.get(config.apiURL.path + config.apiURL.port + '/user/list', {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data
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
 * Soft delete user by id.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function deleteUser(id) {
    const axios = require('axios')
    const AuthStr = Settings.generateAuthenticationString()
    return axios.delete(config.apiURL.path + config.apiURL.port + '/user/' + id, {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {

            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = 'Connection with server problem!'
                return Promise.reject(errorMessage)
            }
        });
}

/**
 * Get user from API by id.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function getUser(id) {
    const axios = require('axios')
    const AuthStr = Settings.generateAuthenticationString()
    return axios.get(config.apiURL.path + config.apiURL.port + '/user/' + id, {headers: {Authorization: AuthStr}})
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = 'Connection with server problem!'
                return Promise.reject(errorMessage)
            }
        });
}
