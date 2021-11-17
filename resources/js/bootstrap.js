window._ = require('lodash');

import Toastify from "toastify-js/src/toastify-es";

window.showToast = function(message, type){
    Toastify({
        text: message,
        duration: 1500,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        className: type, // "info"
        stopOnFocus: true, // Prevents dismissing of toast on hover
        onClick() {} // Callback after click
    }).showToast()
};



/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

const responseHandler = response => {
    return response;
};

const errorHandler = error => {
    if(error.response && [422, 404].includes(error.response.status)) {
        showToast(error.response.data.message, 'error');
    }
    
    return Promise.reject(error);
};

window.axios.interceptors.response.use(
    (response) => responseHandler(response),
    (error) => errorHandler(error)
 );

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
