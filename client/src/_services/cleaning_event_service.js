export const cleaningEventServices = {
    getCleaningEvent,
};

function getCleaningEvent(id) {
    var axios = require("axios");
    var token = sessionStorage.getItem("token");
    var AuthStr = "Bearer ".concat(token);
    return axios
        .get("http://localhost:8000/cleaning/" + id, {
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
