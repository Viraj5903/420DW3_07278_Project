function clearExampleForm() {
    $('#example-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("example-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#example-form")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length !== 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}

function getFormDataAsJson() {
    return JSON.stringify({
                              id: $("#example-id").val(),
                              dayOfTheWeek: $("#example-day-of-week").val(),
                              description: $("#example-description").val(),
                              creationDate: $("#example-creation-date").val(),
                              lastModificationDate: $("#example-modification-date").val(),
                              deletionDate: $("#example-deletion-date").val()
                          });
}

function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("id", $("#example-id").val());
    formData.set("dayOfTheWeek", $("#example-day-of-week").val());
    formData.set("description", $("#example-description").val());
    formData.set("creationDate", $("#example-creation-date").val());
    formData.set("lastModificationDate", $("#example-modification-date").val());
    formData.set("deletionDate", $("#example-deletion-date").val());
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#example-id").val(entityObject.id);
    }
    if ('dayOfTheWeek' in entityObject) {
        $("#example-day-of-week").val(entityObject.dayOfTheWeek);
    }
    if ('description' in entityObject) {
        $("#example-description").val(entityObject.description);
    }
    if ('creationDate' in entityObject) {
        $("#example-creation-date").val(entityObject.creationDate);
    }
    if ('lastModificationDate' in entityObject) {
        $("#example-modification-date").val(entityObject.lastModificationDate);
    }
    if ('deletionDate' in entityObject) {
        $("#example-deletion-date").val(entityObject.deletionDate);
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

/**
 * Function that loads an example entity from the server & database based on the selected value from a selector.
 *
 * USES jQuery.ajax()
 */
function loadExampleRecord() {
    let selectedRecordId = document.getElementById("example-selector").value;
    
    const options = {
        "url": `${EXAMPLE_API_URL}?exampleId=${selectedRecordId}`,
        "method": "get",
        "dataType": "json"
    };
    
    // Use of jQuery.ajax() - see other javascript methods for XMLHttpRequest examples
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

/**
 * Function to send an example entity creation AJAX request to the server
 *
 * USES XMLHttpRequest with urlencoded request data and JSON response data
 */
function createRecord() {
    let request = new XMLHttpRequest();
    request.open("POST", `${EXAMPLE_API_URL}`);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Accept", "application/json");
    request.onreadystatechange = (event) => {
        if (request.readyState === 4) {
            if (request.status < 300) {
                let parsedJson = JSON.parse(request.responseText);
                
                // create new selection option in the selector.
                // this only makes sense for creation
                let selector = document.getElementById("example-selector");
                let newOptionElement = document.createElement("option");
                newOptionElement.value = parsedJson.id;
                newOptionElement.innerHTML = parsedJson.dayOfTheWeek;
                selector.appendChild(newOptionElement);
                selector.value = parsedJson.id;
                
                // fill the main form with retrieved data
                fillFormFromResponseObject(parsedJson);
                
                
            } else if (request.status >= 300 && request.status < 400) {
                let redirectionUrl = request.getResponseHeader("Location");
                if (redirectionUrl) {
                    window.location = redirectionUrl;
                }
            } else if (request.status >= 400) {
                displayResponseError(JSON.parse(request.responseText));
            }
        }
    };
    request.send(getFormDataAsUrlEncoded());
    
}


/**
 * Function to send an example entity update AJAX request to the server
 *
 * USES XMLHttpRequest with JSON request data and JSON response data
 */
function updateRecord() {
    let request = new XMLHttpRequest();
    request.open("PUT", `${EXAMPLE_API_URL}`);
    request.setRequestHeader("Content-Type", "application/json");
    request.setRequestHeader("Accept", "application/json");
    request.onreadystatechange = (event) => {
        if (request.readyState === 4) {
            if (request.status < 300) {
                let parsedJson = JSON.parse(request.responseText);
                fillFormFromResponseObject(parsedJson);
                
            } else if (request.status >= 300 && request.status < 400) {
                let redirectionUrl = request.getResponseHeader("Location");
                if (redirectionUrl) {
                    window.location = redirectionUrl;
                }
            } else if (request.status > 400) {
                displayResponseError(JSON.parse(request.responseText));
            }
        }
    };
    request.send(getFormDataAsJson());
}

/**
 * Function to send an example entity deletion AJAX request to the server
 *
 * USES XMLHttpRequest with urlencoded request data and JSON response data
 */
function deleteRecord() {
    let request = new XMLHttpRequest();
    request.open("DELETE", `${EXAMPLE_API_URL}`);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Accept", "application/json");
    request.onreadystatechange = (event) => {
        if (request.readyState === 4) {
            if (request.status < 300) {
                // expected behavior is 204 NO CONTENT response (sucessful deletion, no data in response)
                
                // remove the option from the selector:
                // first, get the id of the example record that was deleted
                let formIdValue = document.getElementById("example-id").value;
                // if there was an id value (should be the case, since its needed for deletion) proceed with the option removal
                // from the selector
                if (formIdValue) {
                    // get the selector element
                    let selector = /** @type {HTMLSelectElement} */ document.getElementById("example-selector");
                    // then, iterate on the options of the selector, filter those whose value matches the id to delete
                    // Note: voluntary non-identity equality check ( == instead of === ): disable warning
                    // noinspection EqualityComparisonWithCoercionJS
                    [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => elem.remove());
                    
                    selector.value = "";
                }
                
                // Clear the main form for future operations
                clearExampleForm();
                
            } else if (request.status >= 300 && request.status < 400) {
                let redirectionUrl = request.getResponseHeader("Location");
                if (redirectionUrl) {
                    window.location = redirectionUrl;
                }
            } else if (request.status > 400) {
                displayResponseError(JSON.parse(request.responseText));
            }
        }
    };
    request.send(getFormDataAsUrlEncoded());
}

document.getElementById("view-instance-button").onclick = loadExampleRecord;
document.getElementById("clear-button").onclick = clearExampleForm;
document.getElementById("create-button").onclick = createRecord;
document.getElementById("update-button").onclick = updateRecord;
document.getElementById("delete-button").onclick = deleteRecord;
$("#example-form").on("change", ":input", updateClearButtonState);