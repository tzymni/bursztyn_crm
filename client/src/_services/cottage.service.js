export const cottageService = {
    saveCottage,
    getCottage,
    deleteCottage,
    getCottages,
};



function saveCottage(data) {

    if (data.id > 0) {
        return updateCottage(data);
    } else {
        return createCottage(data);
    }
}

function updateCottage(data) {
    var axios = require('axios');
    var token = sessionStorage.getItem('token');
    var AuthStr = 'Bearer '.concat(token);
    return axios.put('http://localhost:8000/cottage/' + data.id, data, {headers: {Authorization: AuthStr}})
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

function createCottage(data) {
    var token = sessionStorage.getItem('token');
    var AuthStr = 'Bearer '.concat(token);

    return axios.post('http://localhost:8000/cottage/add', data, {headers: {Authorization: AuthStr}})
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

function getCottages() {

    var axios = require('axios');
    var token = sessionStorage.getItem('token');
    var AuthStr = 'Bearer '.concat(token);

    return axios.get('http://localhost:8000/cottage/list', {headers: {Authorization: AuthStr}})
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

function deleteCottage(id) {
    var axios = require('axios');
    var token = sessionStorage.getItem('token');
    var AuthStr = 'Bearer '.concat(token);
    return axios.delete('http://localhost:8000/cottage/' + id, {headers: {Authorization: AuthStr}})
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

function getCottage(id) {

    var axios = require('axios');
    var token = sessionStorage.getItem('token');
    var AuthStr = 'Bearer '.concat(token);
    return axios.get('http://localhost:8000/cottage/' + id, {headers: {Authorization: AuthStr}})
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
