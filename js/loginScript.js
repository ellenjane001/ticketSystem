
$(document).ready(function () {



    $('#loginBtn').click(function (e) {
        e.preventDefault();
        var username = document.getElementById("uname").value;
        var password = document.getElementById('pwd').value;
        let dateNtime = document.getElementById('dateNtime').value;
        var val = "login";
        let action = 'Logged in';
        console.log(username);
        console.log(password);

        if (username === "" && password === "") {
            console.log("must input values")
        } else {
            $.ajax({
                url: "accounts.php",
                method: "post",
                data: {
                    username: username,
                    password: password,
                    requestType: val,
                    dateNtime: dateNtime,
                    action: action
                },
                datatype: JSON,
                success: function (data) {
                    console.log(data);
                    $('#message').html(data);
                }

            });
        }


    });


});
