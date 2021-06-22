export const userPresenceService = {
    saveUserPresence,
    getUserPresenceEvent,
    getUserPresencesByCleaningEvent
};

import {Settings} from "@/_services/settings";
import {config} from "@/config";
const axios = require("axios")
/**
 * Save user.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function saveUserPresence(data) {

    let userJson = sessionStorage.getItem('user')
    let user = JSON.parse(userJson)
    let user_id = user.id
    data.created_by_id = user_id

    if (data.id > 0) {
        return updateUserPresence(data);
    } else {
        return createUserPresence(data);
    }
}

/**
 * Update user by id.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function updateUserPresence(data) {
    const axios = require('axios');
    const AuthStr = Settings.generateAuthenticationString()
    return axios.put(config.apiURL.path + config.apiURL.port + '/user_presence/' + data.id, data, {headers: {Authorization: AuthStr}})
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
function createUserPresence(data) {
    const axios = require('axios')
    const AuthStr = Settings.generateAuthenticationString()
    return axios.post(config.apiURL.path + config.apiURL.port + '/user_presence', data, {headers: {Authorization: AuthStr}})
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
 * Get user presences by cleaning event id.
 *
 * @param cleaning_id
 */
function getUserPresencesByCleaningEvent(cleaning_id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/user_presence/cleaning/" + cleaning_id , {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = "Connection with server problem!"
                return Promise.reject(errorMessage)
            }
        });

}

/**
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function getUserPresenceEvent(id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/event/" + id + "/type/"+config.event.userPresencesType, {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data
                return Promise.reject(errorData.error.message)
            } else {
                const errorMessage = "Connection with server problem!"
                return Promise.reject(errorMessage)
            }
        });
}


