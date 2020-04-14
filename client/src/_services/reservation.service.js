export const reservationService = {
    saveReservation
};

const axios = require('axios');
const token = sessionStorage.getItem('token');
const AuthStr = 'Bearer '.concat(token);
const RESERVATION_TYPE = 'reservation';

function saveReservation(data) {

    if (data.id > 0) {
        return null;
    } else {
        return createReservation(data);
    }
}

function createReservation(data) {

    data.type = RESERVATION_TYPE;
    let userJson = sessionStorage.getItem('user');
    let user = JSON.parse(userJson);
    let user_id = user.id;
    data.user_id = user_id;

    return axios.post('http://localhost:8000/event/addReservation', data, {headers: {Authorization: AuthStr}})
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
