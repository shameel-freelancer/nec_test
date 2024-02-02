$("#signupForm").submit((e) => {
    e.preventDefault();
    const submitButton = $("#signupSubmit");
    submitButton.attr("disabled", true);
    const signupError = $("#signupError");
    $("#signupForm .error-border").removeClass("error-border");
    signupError.addClass("d-none");
    if($("#signupPassword").val() != $("#signupConfirmPassword").val()) {
        $("#signupConfirmPassword").addClass("error-border");
        signupError.removeClass("d-none").text("Confirm password doesn't match with Password");
        return;
    }
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
            signupError.addClass("alert-success").html(res.message).removeClass("alert-danger d-none");
            if(res.redirect){
                setTimeout(() => {
                    location.href = res.redirect;
                }, 3000);
            }
            return;
        }
        signupError.removeClass("d-none").html(res.message);
        $(res.errorFields).addClass("error-border");
    }).fail((xhr) => {
        console.error(xhr.responseText);
        signupError.removeClass("d-none").text("Something went wrong!!!");
    }).always(() => {
        submitButton.removeAttr("disabled");
    });
});