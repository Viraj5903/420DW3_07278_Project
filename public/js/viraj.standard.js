$(".nav-bar-entry").on("click", (event) => {
    let navigationUrl = $(event.currentTarget).data("url");
    let type = $(event.currentTarget).data("type");
    let httpMethod = $(event.currentTarget).data("method");
    if (typeof httpMethod === "undefined") {
        httpMethod = "get";
    }
    
    if (typeof type !== "undefined" && type === "api") {
        $.ajax(navigationUrl, {
            method: httpMethod,
            dataType: "json"
        }).done((data, status, jqXHR) => {
            if ("navigateTo" in data) {
                window.location = data.navigateTo;
            }
        });
    } else {
        window.location = navigationUrl;
    }
    
});

function displayResponseError(responseErrorObject) {
    let errorContainer = $(".error-display");
    let classnameContainer = $("#error-class");
    let messageContainer = $("#error-message");
    let previousContainer = $("#error-previous");
    let stacktraceContainer = $("#error-stacktrace");
    if ('exception' in responseErrorObject && typeof responseErrorObject.exception === "object") {
        let exception = responseErrorObject.exception;
        classnameContainer.empty();
        messageContainer.empty();
        previousContainer.empty();
        if ('exceptionClass' in exception) {
            classnameContainer.html(exception.exceptionClass);
        }
        if ('message' in exception) {
            messageContainer.html(exception.message);
            // alert(exception.message);
        }
        while ('previous' in exception && typeof exception.previous === "object") {
            exception = exception.previous;
            if ('exceptionClass' in exception && 'message' in exception) {
                previousContainer.append(`Caused by: ${exception.exceptionClass}: ${exception.message}<br/>`);
            }
        }
    }
    stacktraceContainer.empty();
    if ('stacktrace' in responseErrorObject) {
        stacktraceContainer.html(responseErrorObject.stacktrace.replace(/\r\n/g, '\n'));
    }
    // errorContainer.slideToggle().delay(1000).slideToggle();
    // errorContainer.slideToggle().delay(5000).slideToggle();
}