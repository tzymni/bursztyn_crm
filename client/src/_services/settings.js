export const Settings = {
    getSystemVersion,
    getUserFromSession
};

// TODO add global variable with main API url
const systemVersion = '0.20.1';

function getSystemVersion() {
    return systemVersion;
}

function getUserFromSession() {
    let userFromSession = sessionStorage.getItem("user");
    return JSON.parse(userFromSession);
}
