<?php
$contrasena_plana = 'admin12345'; // Elige una contraseña fuerte
$hash = password_hash($contrasena_plana, PASSWORD_BCRYPT);
echo $hash;
?>