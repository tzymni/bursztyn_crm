export const reservationService = {
    saveReservation,
    getEvents,
    getReservation,
    getReservationsGroupedByCottages,
    checkAvailability
};

import {config} from "@/config";
import {Settings} from "@/_services/settings";

/**
 *
 * @type {AxiosStatic | {CancelTokenSource: CancelTokenSource, CancelStatic: CancelStatic, AxiosProxyConfig: AxiosProxyConfig, Canceler: Canceler, AxiosStatic: AxiosStatic, AxiosRequestConfig: AxiosRequestConfig, AxiosTransformer: AxiosTransformer, Cancel: Cancel, AxiosInstance: AxiosInstance, AxiosError: AxiosError, Method: Method, AxiosPromise: AxiosPromise, CancelTokenStatic: CancelTokenStatic, AxiosBasicCredentials: AxiosBasicCredentials, ResponseType: ResponseType, CancelToken: CancelToken, AxiosInterceptorManager: AxiosInterceptorManager, AxiosResponse: AxiosResponse, AxiosAdapter: AxiosAdapter, readonly default: AxiosStatic}}
 */
const axios = require('axios');

/**
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function saveReservation(data) {

    if (data.id > 0) {
        return updateReservation(data);
    }
}

/**
 * Get events with defined type from API.
 *
 * @param type
 * @returns {Promise<AxiosResponse<any>>}
 */
function getEvents(type) {

    const AuthStr = Settings.generateAuthenticationString()

    if (type == null) {
        type = config.event.allType
    }

    return axios.get(config.apiURL.path + config.apiURL.port + '/event/list/type/' + type, {headers: {Authorization: AuthStr}})
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
        })
}

/**
 * Get all active reservations from API grouped by cottages.
 *
 * @returns {Promise<AxiosResponse<any>>}
 */
function getReservationsGroupedByCottages() {

    const AuthStr = Settings.generateAuthenticationString()

    return axios.get(config.apiURL.path + config.apiURL.port + '/reservation/list/groupBy/cottages', {headers: {Authorization: AuthStr}})
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
 * Update reservation.
 *
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
function updateReservation(data) {

    const AuthStr = Settings.generateAuthenticationString()

    data.type = config.event.reservationType
    let userJson = sessionStorage.getItem('user')
    let user = JSON.parse(userJson)
    let user_id = user.id
    data.user_id = user_id

    return axios
        .put(config.apiURL.path + config.apiURL.port + "/event/"+config.event.reservationType+"/" + data.id, data, {
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
 * Get reservation from API by event id.
 *
 * @param eventId
 * @returns {Promise<AxiosResponse<any>>}
 */
function getReservation(eventId) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/event/" + eventId + "/type/" + config.event.reservationType, {
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
 * Check if is possible to reservation cottage between dates.
 *
 * @param dateFrom
 * @param dateTo
 * @param cottageIds
 * @returns {Promise<AxiosResponse<any>>}
 */
function checkAvailability(dateFrom, dateTo, cottageIds) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios
        .get(config.apiURL.path + config.apiURL.port + "/reservation/availability/cottage_ids/" + cottageIds + "/date_from/" + dateFrom + "/date_to/" + dateTo, {
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