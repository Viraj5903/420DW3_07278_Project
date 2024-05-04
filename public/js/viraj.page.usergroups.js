
document.getElementById("clear-button").onclick = clearForm;

function clearForm() {
    $("#user_group-form").get(0).reset();
    $("#description").val("");
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("user_group-selector").value = "";
}

document.getElementById("view-user_group-button").onclick = loadUserGroup;


function loadUserGroup() {
    const selectedRecordId = document.getElementById("user_group-selector").value;
    
    const options = {
        "url": `${API_USER_GROUP_URL}?id=${selectedRecordId}`,
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
    if ('groupName' in entityObject) {
        $("#group_name").val(entityObject.groupName);
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
    
    // uncheck all authors
    $(".user_group-permissions").each((index, inputElem) => $(inputElem).prop("checked", false));
    
    if ('permissions' in entityObject) {
        if (typeof entityObject.permissions === "object") {
            console.log(Object.keys(entityObject.permissions));
            Object.keys(entityObject.permissions).forEach((value) => $(`#user_group-permission-${value}`)
                .prop("checked", true));
        }
    }
    
    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

function getFormDataAsUrlEncoded() {
    const formData = new FormData();
    formData.set("id", $("#id").val());
    formData.set("group_name", $("#group_name").val());
    formData.set("description", $("#description").val());
    const permissions = [];
    $(".user_group-permissions").each((index, inputElem) => {
        console.log(inputElem);
        if ($(inputElem).prop("checked")) {
            // console.log("checked");
            permissions.push($(inputElem).val());
        }
    });
    formData.set("permissions", permissions);
    console.log(permissions);
    console.log(Object.fromEntries(formData));
    return (new URLSearchParams(formData)).toString();
}

document.getElementById("create-button").onclick = createUserGroup;

function createUserGroup() {
    const options = {
        "url": `${API_USER_GROUP_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         
         // Adding the new created user in select option.
         if ('groupName' in data) {
             const selector = document.getElementById("user_group-selector");
             const newOptionElement = document.createElement("option");
             newOptionElement.value = data.id;
             newOptionElement.innerHTML = `${data.groupName}`;
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

document.getElementById("delete-button").onclick = deleteUserGroup;

function deleteUserGroup() {
    const options = {
        "url": `${API_USER_GROUP_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         console.log("Received data: ", data);
         const formIdValue = document.getElementById("id").value;
         if (formIdValue) {
             const selector = /** @type {HTMLSelectElement} */ document.getElementById("user_group-selector");
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

document.getElementById("update-button").onclick = updateUserGroup;

function updateUserGroup() {
    const options = {
        "url": `${API_USER_GROUP_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };
    
    $.ajax(options)
     .done((data, status, jqXHR) => {
         
         console.log("Received data: ", data);
         
         // Replace the text in the selector with the updated values
         const formIdValue = document.getElementById("id").value;
         if ('username' in data) {
             const selector = /** @type {HTMLSelectElement} */ document.getElementById("user_group-selector");
             // Note: voluntary non-identity equality check ( == instead of === ): disable warning
             // noinspection EqualityComparisonWithCoercionJS
             // The JavaScript spread operator (...) allows us to quickly copy all or part of an existing array or object into another array or object.
             [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                 elem.innerHTML = `${data.username}`;
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
$("#user_group-form").on("change", ":input", updateClearButtonState);