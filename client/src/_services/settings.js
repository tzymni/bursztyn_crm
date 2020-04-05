export const Settings = {
    getSystemVersion,
    getUserFromSession
};

const systemVersion = '0.8.1';

function getSystemVersion() {
    return systemVersion;
}

function getUserFromSession() {
    let userFromSession = sessionStorage.getItem("user");
    return JSON.parse(userFromSession);
}
