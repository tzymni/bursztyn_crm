export const cleaningEventServices = {
    getCleaningEvent,
    getCleaningEventDetails,
    getAllCleaningEvents,
    getFutureCleaningEventsWithDetails
};


function getCleaningEventDetails(id) {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/cleaning/details/" + id, {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}

function getAllCleaningEvents() {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/cleaning/list/all/" , {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}

function getCleaningEvent(id) {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/event/" + id + "/type/CLEANING" , {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}

function getFutureCleaningEventsWithDetails() {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/next-cleanings", {
            headers: {Authorization: AuthStr},
        })
        .then(function (response) {
            return response.data;
        })
        .catch(function (error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}
