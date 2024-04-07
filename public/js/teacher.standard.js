
const header = document.getElementById("header");
const main = document.getElementById("main");


// <editor-fold defaultstate="collapsed" desc="STICKY HEADER HANDLING">

function ajustHeader() {
    let headerHeight = header.offsetHeight;
    if (window.scrollY > header.offsetTop) {
        header.classList.add("sticky");
        main.style.marginTop = `${headerHeight}px`;
    } else {
        header.classList.remove("sticky");
        main.style.marginTop = "0px";
    }
}
$(window).on("scroll", ajustHeader);
$(window).on("resize", ajustHeader);

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="NAVBAR NAVIGATION">

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

// </editor-fold>