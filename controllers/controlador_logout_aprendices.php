<?php

session_start();


session_unset();

session_destroy();

echo json_encode([
    "success" => true,
    "message" => "El juego fue finalizado, muchas gracias por participar "
]);
exit();
