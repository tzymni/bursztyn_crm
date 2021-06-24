import {config} from "@/config";
import {Settings} from "@/_services/settings";

/**
 *
 * @type {{getAllCleaningEvents: (function(): Promise<AxiosResponse<any>>), getCleaningEventDetails: (function(*): Promise<AxiosResponse<any>>), getFutureCleaningEventsWithDetails: (function(): Promise<AxiosResponse<any>>), getCleaningEvent: (function(*): Promise<AxiosResponse<any>>)}}
 */
export const cleaningEventServices = {
    getCleaningEventDetails,
    getAllCleaningEvents,
    getFutureCleaningEventsWithDetails
};
const axios = require("axios")

/**
 * Get cleaning details information from API.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function getCleaningEventDetails(id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/cleaning/details/" + id, {
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
 * Get all cleaning events from API.
 *
 * @returns {Promise<AxiosResponse<any>>}
 */
function getAllCleaningEvents() {

    const AuthStr = Settings.generateAuthenticationString()

    return axios
        .get(config.apiURL.path + config.apiURL.port + "/cleaning/list/all/", {
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
 * Get next cleaning events.
 *
 * @returns {Promise<AxiosResponse<any>>}
 */
function getFutureCleaningEventsWithDetails() {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/next-cleanings", {
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
