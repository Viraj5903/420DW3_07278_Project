$("#loginButton").on("click", (event) => {
    event.stopPropagation();
    let data = $("#loginForm").serialize();
    console.log(data);
    $.ajax(API_LOGIN_URL, {
        method: "post",
        dataType: "json",
        data: data
    }).done((data, status, jqXHR) => {
        console.log(data);
        if ("navigateTo" in data) {
            window.location = data.navigateTo;
        }
    }).fail((jqXHR, textStatus, errorThrown) => {
        console.log(jqXHR);
        // console.log(textStatus);
        if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
            displayResponseError(jqXHR.responseJSON);
        }
        // alert(jqXHR.responseJSON.exception.message);
    });
});