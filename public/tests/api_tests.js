$(document).ready(() => {
    const url = 'https://nsdb.local/api/groups?XDEBUG_SESSION_START=PHPSTORM&whatever';
    const options = {
        method: 'GET',
        data: {
            "active": true
        },
        success: function (returndata, status, jqxhr) {
            $("body").text(JSON.stringify(returndata))
        }

    };

    $.ajax(url, options);


});

