$(document).ready(() => {
    const url = 'https://nsdb.local/api/users?XDEBUG_SESSION_START=PHPSTORM';
    const options = {
        method: 'GET',
        data: {
            "active": 0
            // updates: {
            //     1: {
            //         'lastname': 'Huhl'
            //     },
            //     3: {
            //         'firstName': 'Larissa'
            //     }
            // }
        },
        success: function (returndata, status, jqxhr) {
            $("body").text(JSON.stringify(returndata))
        }

    };

    $.ajax(url, options);


});

