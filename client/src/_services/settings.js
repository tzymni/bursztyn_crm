import {config} from "@/config";

/**
 *
 * @type {{getSystemVersion: (function(): string), getUserFromSession: (function(): any)}}
 */
export const Settings = {
    getSystemVersion,
    getUserFromSession,
    generateAuthenticationString
};

/**
 * Get current system version from config.
 *
 * @returns {string}
 */
function getSystemVersion() {
    return config.systemVersion;
}

/**
 * Get user from session storage.
 *
 * @returns {any}
 */
function getUserFromSession() {
    let userFromSession = sessionStorage.getItem("user");
    return JSON.parse(userFromSession);
}

/**
 * Generate authentication string by token from session storage.
 *
 * @returns {string}
 */
function generateAuthenticationString() {
    const token = sessionStorage.getItem("token")
    return "Bearer ".concat(token)
}
