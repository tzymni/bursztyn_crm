export const reservationService = {
    saveReservation,
    getEvents,
    getReservation,
    getReservationsGroupedByCottages
};

const axios = require('axios');
const RESERVATION_TYPE = 'reservation';

function saveReservation(data) {

    if (data.id > 0) {
        return updateReservation(data);
    } else {
        return createReservation(data);
    }
}

function getEvents(type) {
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    if(type == null) {
        type = 'ALL';
    }

    return axios.get('http://localhost:8000/event/list/type/'+type, {headers: {Authorization: AuthStr}})
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

function getReservationsGroupedByCottages() {
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    return axios.get('http://localhost:8000/reservation/list/groupBy/cottages', {headers: {Authorization: AuthStr}})
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



function createReservation(data) {
    const axios = require('axios');
    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

    data.type = RESERVATION_TYPE;
    let userJson = sessionStorage.getItem('user');
    let user = JSON.parse(userJson);
    let user_id = user.id;
    data.user_id = user_id;

    return axios.post('http://localhost:8000/event/reservation', data, {headers: {Authorization: AuthStr}})
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

function updateReservation(data) {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);

    data.type = RESERVATION_TYPE;
    let userJson = sessionStorage.getItem('user');
    let user = JSON.parse(userJson);
    let user_id = user.id;
    data.user_id = user_id;

    return axios
        .put("http://localhost:8000/event/reservation/" + data.id, data, {
            headers: { Authorization: AuthStr },
        })
        .then(function(response) {
            return response.data;
        })
        .catch(function(error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}

function getReservation(eventId) {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/event/" + eventId+"/type/reservation", {
            headers: { Authorization: AuthStr },
        })
        .then(function(response) {
            return response.data;
        })
        .catch(function(error) {
            if (error.response) {
                const errorData = error.response.data;
                return Promise.reject(errorData.error.message);
            } else {
                const errorMessage = "Connection with server problem!";
                return Promise.reject(errorMessage);
            }
        });
}