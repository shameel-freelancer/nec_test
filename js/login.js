$("#loginForm").submit((e) => {
    e.preventDefault();
    const submitButton = $("#loginSubmit");
    submitButton.attr("disabled", true);
    const loginError = $("#loginError");
    $("#loginForm .error-border").removeClass("error-border");
    loginError.addClass("d-none");
    $.ajax({
        url: "core/main.php",
        data: $(e.currentTarget).serialize(),
        dataType: "json",
        type: "post"
    }).done((res) => {
        if(res.redirect){
            setTimeout(() => {
                location.href = res.redirect;
            }, res.status ? 1 : 3000);
        }
        if(!res.status) {
            loginError.removeClass("d-none").html(res.message);
            $(res.errorFields).addClass("error-border");
        }
    }).fail((xhr) => {
        console.error(xhr.responseText);
        loginError.removeClass("d-none").text("Something went wrong!!!");
    }).always(() => {
        submitButton.removeAttr("disabled");
    });
});