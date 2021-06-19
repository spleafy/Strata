$("document").ready(() => {
    $.get("actions.php", {languageRequest: "bg"}, function(_response) {
        $("body").html(_response);
    });    
})

