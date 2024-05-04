function clearForm() {
    $("#permission-form").get(0).reset();
    $("#description").val("");
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("permission-selector").value = "";
}

document.getElementById("clear-button").onclick = clearForm;
document.getElementById("view-permission-button").onclick = loadPermission;

function loadPermission() {
    const selectedRecordId = document.getElementById("permission-selector").value;
    
    const options = {
        "url": `${API_PERMISSION_URL}?id=${selectedRecordId}`,
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

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#id").val(entityObject.id);
    }
    if ('uniquePermission' in entityObject) {
        $("#unique_permission").val(entityObject.uniquePermission);
    }
    if ('permissionName' in entityObject) {
        $("#permission_name").val(entityObject.permissionName);
    }
    if ('description' in entityObject) {
        $("#description").text(entityObject.description);
    }
    if ('creationDate' in entityObject) {
        $("#date_created").val(entityObject.creationDate);
    }
    if ('lastModificationDate' in entityObject) {
        $("#date_modified").val(entityObject.lastModificationDate);
    }
    
    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

function getFormDataAsUrlEncoded() {
    const formData = new FormData();
    formData.set("id", $("#id").val());
    formData.set("unique_permission", $("#unique_permission").val());
    formData.set("permission_name", $("#permission_name").val());
    formData.set("description", $("#description").val());
    console.log(Object.fromEntries(formData));
    console.log($("#description").text());
    return (new URLSearchParams(formData)).toString();
}

document.getElementById("create-button").onclick = createPermission;

function createPermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         // Adding the new created permission in select option.
         if ('uniquePermission' in data) {
             const selector = document.getElementById("permission-selector");
             const newOptionElement = document.createElement("option");
             newOptionElement.value = data.id;
             newOptionElement.innerHTML = `${data.uniquePermission}`;
             selector.appendChild(newOptionElement);
             selector.value = data.id;
         }
         fillFormFromResponseObject(data);
     })
     .fail((jqXHR, textstatus, error) => {
         console.log(jqXHR);
         if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
             displayResponseError(jqXHR.responseJSON);
         }
         console.log("Error");
     });
}

document.getElementById("delete-button").onclick = deletePermission;

function deletePermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         const formIdValue = document.getElementById("id").value;
         if (formIdValue) {
             const selector = /** @type {HTMLSelectElement} */ document.getElementById("permission-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             // The JavaScript spread operator (...) allows us to quickly copy all or part of an existing array or object into another array or object.
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

document.getElementById("update-button").onclick = updatePermission;

function updatePermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         
         console.log("Received data: ", data);
         
         // Replace the text in the selector with the updated values
         let formIdValue = document.getElementById("id").value;
         if ('uniquePermission' in data) {
             const selector = /** @type {HTMLSelectElement} */ document.getElementById("permission-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             // The JavaScript spread operator (...) allows us to quickly copy all or part of an existing array or object into another array or object.
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                 elem.innerHTML = `${data.uniquePermission}`;
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


// The on() method attaches one or more event handlers for the selected elements and child elements.
$("#permission-formm").on("change", ":input", updateClearButtonState);