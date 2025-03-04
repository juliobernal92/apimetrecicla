<?php
// Función para encriptar y establecer una cookie
// Función para encriptar el valor de una cookie
function encryptCookie($value) {
    $cipher_algo = 'AES-256-CBC';
    $encryption_key = '2h,~o[y5u5Z~6zO`o?9*:JE*=iML8</A'; // Clave de cifrado (debes mantenerla segura)
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $encrypted_value = openssl_encrypt($value, $cipher_algo, $encryption_key, 0, $iv);

    // Devuelve el valor cifrado junto con el IV para poder descifrarlo más adelante
    return base64_encode($iv . $encrypted_value);
}





