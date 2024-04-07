
$("#loginButton").on("click", (event) => {
    event.stopPropagation();
    let data = $("#loginForm").serialize();
    $.ajax(API_LOGIN_URL, {
        method: "post",
        dataType: "json",
        data: data
    }).done((data, status, jqXHR) => {
        if ("navigateTo" in data) {
            window.location = data.navigateTo;
        }
    });
});