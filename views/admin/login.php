<div class="container">
    <div id="login-row" class="row justify-content-center align-items-center mt-5">
        <div id="login-column" class="col-md-6">
            <div id="login-box" class="col-md-12">
                <form id="login-form" class="form" action="" method="post">
                    <h3 class="text-center text-primary">Login</h3>
                    <div class="alert mb-3" role="alert"></div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username : </label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password : </label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="text-end">
                        <input type="button" name="login" class="btn btn-outline-primary btn-md" value="login">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function submitLogin(event) {
            //event.currentTarget.disabled = true;
            document.querySelector('input[name="login"]').disabled = true;

            if (!document.querySelector('input[name="username"]').value || !document.querySelector('input[name="password"]').value) {
                document.querySelector('.alert').classList.add("alert-warning");
                document.querySelector('.alert').classList.remove("alert-success");
                document.querySelector('.alert').innerHTML = "Please check username and password";
                document.querySelector('input[name="login"]').disabled = false;
                document.querySelector('input[name="username"]').focus();
            } else {

                var xmlhttp = new XMLHttpRequest(); // new HttpRequest instance 
                var theUrl = window.location.origin + "/api/v1/login.php";
                xmlhttp.open("POST", theUrl);
                xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xmlhttp.onreadystatechange = function() { //Call a function when the state changes.
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        //alert(xmlhttp.responseText);
                        var result = JSON.parse(xmlhttp.responseText);
                        if (result.success) {
                            document.querySelector('.alert').classList.add("alert-success");
                            document.querySelector('.alert').classList.remove("alert-warning");
                            document.querySelector('.alert').innerHTML = result.message;

                            var timeToAdd = 1000 * 60 * 60 * 24 * 1; // * 4 * 6; //milliseconds * seconds * minutes * hours * days * weeks * months
                            var date = new Date();
                            var expiryTime = parseInt(date.getTime()) + timeToAdd;
                            date.setTime(expiryTime);
                            var utcTime = date.toUTCString();
                            document.cookie = "STOKEN=" + result.token + "; expires=" + utcTime + ";";

                            setTimeout(function() {
                                window.location.href = window.location.origin + "/admin2/";
                            }, 3000);
                        } else {
                            document.querySelector('.alert').classList.add("alert-warning");
                            document.querySelector('.alert').classList.remove("alert-success");
                            document.querySelector('.alert').innerHTML = result.message;
                            document.querySelector('input[name="login"]').disabled = false;
                        }
                    }
                }
                xmlhttp.send(JSON.stringify({
                    "username": document.querySelector('input[name="username"]').value,
                    "password": document.querySelector('input[name="password"]').value
                }));
            }
        }

        document.querySelector('input[name="login"]').onclick = submitLogin;
        document.querySelector('input[name="password"]').addEventListener('keydown', function(event) {
            //console.log('keypressed', this, e);
            //e.target will refer to the actual event target

            //console.log('down in `' + e.target.innerHTML + '` with keycode: ' + (e.keyCode))
            if (event.keyCode == 13) {
                submitLogin(event);
            }
        });

    });
</script>