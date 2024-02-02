$("#profileForm").submit((e) => {
    e.preventDefault();
    const submitButton = $("#profileSubmit");
    submitButton.attr("disabled", true);
    const profileError = $("#profileError");
    $("#profileForm .error-border").removeClass("error-border");
    profileError.addClass("d-none");
    const formData = new FormData(e.currentTarget);
    $.ajax({
        url: "core/main.php",
        data: formData,
        processData: false,
        contentType: false,
        encType: "multipart/form-data",
        dataType: "json",
        type: "post"
    }).done((res) => {
        if(res.status) {
            profileError.addClass("alert-success").html(res.message).removeClass("alert-danger d-none");
            if(res.redirect){
                setTimeout(() => {
                    location.href = res.redirect;
                }, 3000);
            }
            return;
        }
        profileError.removeClass("d-none").html(res.message);
        $(res.errorFields).addClass("error-border");
    }).fail((xhr) => {
        console.error(xhr.responseText);
        profileError.removeClass("d-none").text("Something went wrong!!!");
    }).always(() => {
        submitButton.removeAttr("disabled");
    });
});

$("#logoutButton").click((e) => {
    e.preventDefault();
    const logoutButton = $("#logoutButton");
    logoutButton.attr("disabled", true);
    $.ajax({
        url: "core/main.php",
        data: {type: "logout"},
        dataType: "json",
        type: "post"
    }).done((res) => {
        location.href = res.redirect;
    }).fail((xhr) => {
        console.error(xhr.responseText);
    }).always(() => {
        logoutButton.removeAttr("disabled");
    });
})