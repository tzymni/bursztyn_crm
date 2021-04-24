import {config} from "@/config";
import {Settings} from "@/_services/settings";

export const cottageService = {
    saveCottage,
    getCottage,
    deleteCottage,
    getCottages,
};
const axios = require("axios")

/**
 * Save cottage (update or insert).
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function saveCottage(data) {
    if (data.id > 0) {
        return updateCottage(data)
    } else {
        return createCottage(data)
    }
}

/**
 * Update cottage by id.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function updateCottage(data) {
    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .put(config.apiURL.path + config.apiURL.port + "/cottage/" + data.id, data, {
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
 * Create a new cottage.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function createCottage(data) {
    const AuthStr = Settings.generateAuthenticationString()

    return axios
        .post(config.apiURL.path + config.apiURL.port + "/cottage/add", data, {
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
 * Get all active cottages from API.
 *
 * @returns {Promise<AxiosResponse<any>>}
 */
function getCottages() {

    const AuthStr = Settings.generateAuthenticationString()

    return axios
        .get(config.apiURL.path + config.apiURL.port + "/cottage/list", {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data
        })
        .catch(function (error) {
            if (error.response) {
                const errorMessage = error.response.data.error.message
                return Promise.reject(errorMessage)
            } else {
                const errorMessage = "Connection with server problem!"
                return Promise.reject(errorMessage)
            }
        });
}

/**
 * Soft delete from the system by id.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function deleteCottage(id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .delete(config.apiURL.path + config.apiURL.port + "/cottage/" + id, {
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
 * Get cottage from API by id.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function getCottage(id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/cottage/" + id, {
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
