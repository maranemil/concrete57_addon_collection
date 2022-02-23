// noinspection JSUnusedGlobalSymbols,JSUnresolvedFunction,JSUnresolvedVariable

function getBlog(actionurl, page, username) {
    $('#tumblrfeed-' + username).html('');
    $('#tumblrfeed-' + username + '-loading').css('display', 'block');

    //console.log(actionurl)
    //var actionurl = "/index.php/blank-page/passthru/Main/78/getBlogHTML";

    $.ajax({
        type: 'POST',
        url: actionurl,
        data: ({blogPage: page}),
        dataType: 'html',
        success: function (data) {
            // console.log(data)
            $('#tumblrfeed-' + username + '-loading').css('display', 'none');
            $('#tumblrfeed-' + username).html(data);
        }
    });
}

