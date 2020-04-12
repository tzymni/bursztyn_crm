export const cottageService = {
    saveCottage,
    getCottage,
    deleteCottage,
    getCottages,
};

const axios = require('axios');
const token = sessionStorage.getItem('token');
const AuthStr = 'Bearer '.concat(token);

function saveCottage(data) {

    if (data.id > 0) {
        return updateCottage(data);
    } else {
        return createCottage(data);
    }
}

function updateCottage(data) {
    return axios.put('http://localhost:8000/api/cottage/' + data.id, data, {headers: {Authorization: AuthStr}})
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

    const token = sessionStorage.getItem('token');
    const AuthStr = 'Bearer '.concat(token);

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

    return axios.delete('http://localhost:8000/api/cottage/' + id, {headers: {Authorization: AuthStr}})
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

    return axios.get('http://localhost:8000/api/cottage/' + id, {headers: {Authorization: AuthStr}})
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
