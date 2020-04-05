export const Settings = {
    getSystemVersion,
    getUserFromSession
};

const systemVersion = '0.8.0';

function getSystemVersion() {
    return systemVersion;
}

function getUserFromSession() {
    let userFromSession = sessionStorage.getItem("user");
    return JSON.parse(userFromSession);
}
