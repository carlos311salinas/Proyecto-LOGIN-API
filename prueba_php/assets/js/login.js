$(document).ready(function () {

    $('#loginForm').submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: '../../api/login.php',

            method: 'POST',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    window.location.href = '../../dashboard.php';

                } else {

                    $('#mensaje').html(
                        `<div class="alert alert-danger">
                            ${response.message}
                        </div>`
                    );

                }

            },

            error: function () {

                $('#mensaje').html(
                    `<div class="alert alert-danger">
                        Error del servidor
                    </div>`
                );

            }

        });

    });

});