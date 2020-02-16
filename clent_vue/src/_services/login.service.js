export const loginService = {
    login,
    // logout,
    // getAll
};

function login(username, password) {
    const requestOptions = {
        method: "POST",
        headers:
            {"Content-Type": "application/json",
              "Authorization": 'Basic ' + btoa(username + ":" + password)
            },
        body: JSON.stringify({ username, password })
    };

    return fetch(`http://localhost:8000/api/authenticate`, requestOptions)
        .then(handleResponse)
        .then(response => {
            // login successful if there's a user in the response
            if (response.data.token) {

                let token = response.data.token;
                sessionStorage.setItem("token", token);
            }
            return response;
        });
}

function logout() {
    // remove user from local storage to log user out
    sessionStorage.removeItem("token");
}

// function getAll() {
//     const requestOptions = {
//         method: "GET",
//         headers: authHeader()
//     };
//
//     return fetch(`/users`, requestOptions).then(handleResponse);
// }

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);

        if (!response.ok) {
            if (response.status === 401) {
                // auto logout if 401 response returned from api
                logout();
                window.location.reload(true);
            }

            const error = data.error.message;
            return Promise.reject(error);
        }

        return data;
    });
}
