<h1>Validar token</h1>


<form action="" method="post">
    <label for="">Token:</label>
    <input type="text" name="token">

    <input type="submit" name="validar" value="validar">
</form>
<?php

//recibir los datos del formulario

$datos = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($datos['validar'])) {
    // var_dump($datos);

    $token_array = explode('.', $datos['token']);
    // var_dump($token_array);
    $header = $token_array[0];
    $payload = $token_array[1];
    $firma = $token_array[2];

    $clave = "12edasfeau9hbbhuiby8uv6rt7v8g9hq34s5ed6f7tgy8h";


    $validar_firma = hash_hmac('sha256', "$header.$payload", $clave, true);
    $validar_firma = base64_encode($validar_firma);

    if ($firma == $validar_firma) {
        //validar si esta vencido

        $datos_token = base64_decode($payload); //decodificar payload

        //convertir objetos en arreglos
        $datos_token = json_decode($datos_token);
        // var_dump($datos_token);

        //comparar la data de vencimiento del token con la fecha actual

        if ($datos_token->exp > time()) {
            echo "Token Valido";
        }else{
            echo "Token invalido, token vencido";
        }
    }else{
        echo "Token invalido";

    }
}
?>