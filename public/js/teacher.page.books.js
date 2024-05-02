function clearForm() {
    $('#book-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("book-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#book-form")
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
    formData.set("id", $("#book-id").val());
    formData.set("title", $("#book-title").val());
    formData.set("description", $("#book-description").val());
    formData.set("isbn", $("#book-isbn").val());
    formData.set("publicationYear", $("#book-publication-year").val());
    formData.set("dateCreated", $("#author-date-created").val());
    formData.set("dateLastModified", $("#author-date-last-modified").val());
    formData.set("dateDeleted", $("#author-date-deleted").val());
    $(".book-authors").each((index, inputElem) => {
        console.log(inputElem);
        formData.set(inputElem.name, $(inputElem).prop("checked"));
    });
    console.log(Object.fromEntries(formData));
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#book-id").val(entityObject.id);
    }
    if ('title' in entityObject) {
        $("#book-title").val(entityObject.title);
    }
    if ('description' in entityObject) {
        $("#book-description").val(entityObject.description);
    }
    if ('isbn' in entityObject) {
        $("#book-isbn").val(entityObject.isbn);
    }
    if ('publicationYear' in entityObject) {
        $("#book-publication-year").val(entityObject.publicationYear);
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
    
    // uncheck all authors
    $(".book-authors").each((index, inputElem) => {
        $(inputElem).prop("checked", false)
    });
    
    if ('authors' in entityObject) {
        if (typeof entityObject.authors === "object") {
            console.log(Object.keys(entityObject.authors));
            Object.keys(entityObject.authors).forEach((value) => {
                $(`#book-author-${value}`).prop("checked", true);
            });
        }
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

function loadBook() {
    let selectedRecordId = document.getElementById("book-selector").value;
    
    const options = {
        "url": `${API_BOOK_URL}?bookId=${selectedRecordId}`,
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

function createBook() {
    const options = {
        "url": `${API_BOOK_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         if ('title' in data) {
             let selector = document.getElementById("book-selector");
             let newOptionElement = document.createElement("option");
             newOptionElement.value = data.id;
             newOptionElement.innerHTML = `${data.title}`;
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

function updateBook() {
    const options = {
        "url": `${API_BOOK_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         // Replace the text in the selector with the updated values
         let formIdValue = document.getElementById("book-id").value;
         if ('title' in data) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("book-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                 elem.innerHTML = `${data.title}`;
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

function deleteBook() {
    const options = {
        "url": `${API_BOOK_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         let formIdValue = document.getElementById("book-id").value;
         if (formIdValue) {
             let selector = /** @type {HTMLSelectElement} */ document.getElementById("book-selector");
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

document.getElementById("view-instance-button").onclick = loadBook;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createBook;
document.getElementById("update-button").onclick = updateBook;
document.getElementById("delete-button").onclick = deleteBook;
$("#book-form").on("change", ":input", updateClearButtonState);