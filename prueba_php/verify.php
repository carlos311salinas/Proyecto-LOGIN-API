<?php

$hash = '$2y$10$pzU9bhDZI80YB6H.Xap8A.mzn46o5a6rx5xijlQrCfWpcjJty8doe';

$password = 'Manizales26*';

if (password_verify($password, $hash)) {

    echo "PASSWORD CORRECTA";

} else {

    echo "PASSWORD INCORRECTA";

}