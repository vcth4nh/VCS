$("#login-btn").click(() => {
    $("#loading").css("display", "block");
    $.ajax({
        url: "/api/login",
        type: "POST",
        data: '{"username": "' + $("#uname").val() + '","password": "' + $("#passwd").val() + '"}',
        contentType: "application/json; charset=utf-8",
    }).done((data) => {
        document.write(data);
    });
});
