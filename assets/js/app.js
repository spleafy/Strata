$("document").ready(() => {
    $.get("actions.php", { languageRequest: "bg" }, function (_response) {
        $("body").html(_response);
    });
});

  var images = [
    "./assets/img/header-image-1.png",
    "./assets/img/header-image-2.png",
    "./assets/img/header-image-3.png",
    "./assets/img/header-image-4.png",
  ];
  
  let headerIndex = null, headerImageUrl = null;
  