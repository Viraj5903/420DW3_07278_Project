function clearForm() {
    $('#author-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("author-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#author-form")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length > 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}

function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("id", $("#author-id").val());
    formData.set("firstName", $("#author-first-name").val());
    formData.set("lastName", $("#author-last-name").val());
    formData.set("dateCreated", $("#author-date-created").val());
    formData.set("dateLastModified", $("#author-date-last-modified").val());
    formData.set("dateDeleted", $("#author-date-deleted").val());
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#author-id").val(entityObject.id);
    }
    if ('firstName' in entityObject) {
        $("#author-first-name").val(entityObject.firstName);
    }
    if ('lastName' in entityObject) {
        $("#author-last-name").val(entityObject.lastName);
    }
    if ('dateCreated' in entityObject) {
        $("#author-date-created").val(entityObject.dateCreated);
    }
    if ('dateLastModified' in entityObject) {
        $("#author-date-last-modified").val(entityObject.dateLastModified);
    }
    if ('dateDeleted' in entityObject) {
        $("#author-date-deleted").val(entityObject.dateDeleted);
    }
    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

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
    errorContainer.slideToggle().delay(5000).slideToggle();
    
}

function loadAuthor() {
    let selectedRecordId = document.getElementById("author-selector").value;
    
    const options = {
        "url": `${API_AUTHOR_URL}?authorId=${selectedRecordId}`,
        "method": "get",
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function createAuthor() {
    const options = {
        "url": `${API_AUTHOR_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         if ('firstName' in data && 'lastName' in data) {
             let selector = document.getElementById("author-selector");
             let newOptionElement = document.createElement("option");
             newOptionElement.value = data.id;
             newOptionElement.innerHTML = `${data.firstName} ${data.lastName}`;
             selector.appendChild(newOptionElement);
             selector.value = data.id;
         }
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function updateAuthor() {
    const options = {
        "url": `${API_AUTHOR_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         // Replace the text in the selector with the updated values
         let formIdValue = document.getElementById("author-id").value;
         if ('firstName' in data && 'lastName' in data) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("author-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                 elem.innerHTML = `${data.firstName} ${data.lastName}`;
             });
         }
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

function deleteAuthor() {
    const options = {
        "url": `${API_AUTHOR_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         let formIdValue = document.getElementById("author-id").value;
         if (formIdValue) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("author-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => elem.remove());
             selector.value = "";
         }
         clearForm();
     })
     .fail((jqXHR, textstatus, error) => {
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
     });
}

document.getElementById("view-instance-button").onclick = loadAuthor;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createAuthor;
document.getElementById("update-button").onclick = updateAuthor;
document.getElementById("delete-button").onclick = deleteAuthor;
$("#author-form").on("change", ":input", updateClearButtonState);