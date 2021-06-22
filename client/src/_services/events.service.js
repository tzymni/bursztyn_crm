export const eventsService = {
    deleteEvent
};

import {Settings} from "@/_services/settings";
import {config} from "@/config";
const axios = require("axios")

/**
 * Soft delete event by id.
 *
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function deleteEvent(id) {

    const AuthStr = Settings.generateAuthenticationString()
    return axios.delete(config.apiURL.path + config.apiURL.port + '/event/' + id, {headers: {Authorization: AuthStr}})
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


