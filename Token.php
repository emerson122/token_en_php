<?php
class ALLtoken
{

    public static function Generar($tiempo, $usuario, $correo)
    {
        //json web token lo divido en 3 partes separados por un punto ".": un heeader, un payload y una firma

        // Header indica el tipo de token "JWT" O ALGORITMO UTILIZADO "HS256"

        $header = [
            'alg' => 'HS256',
            'type' => 'JWT'
        ];

        //Convertir el arreglo en un objeto
        $header = json_encode($header);

        //codificar objetos en base64

        $header = base64_encode($header);
        //imprimir el header
        // echo 'Header:'.$header.'<br><br>';

        // un payload o cuerpo del jwt, recibe la información 
        // este contiende la información de la duración del token


        // $duracion = time() + (60 * 60 );
        $duracion = time() + $tiempo;

        // echo "Data actual: ".date("Y-m-d H:i:s").'<br> Vencimiento'.date("Y-m-d H:i:s",$duracion);

        // llenado de payload 
        $payload = [
            // 'iss'=>"localhost",
            // 'aud'=>'localhost',
            'exp' => $duracion,
            'id' => '1',
            'nombre' => $usuario,
            'email' => $correo
        ];

        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        // echo 'Payload:' . $payload . '<br><br>';


        // Para llegar a firmar un token se pega el payload y se codifica con algoritmo sha256, junto con la clave

        $clave = "12edasfeau9hbbhuiby8uv6rt7v8g9hq34s5ed6f7tgy8h";

        // Generar un valor de hash con clave usando un metodo HMAC 


        $firma = hash_hmac('sha256', "$header.$payload", $clave, true);


        //codificar objetos en base64

        $firma = base64_encode($firma);
        //imprimir el firma
        // echo 'firma:' . $firma . '<br><br>';



        return "$header.$payload.$firma";
    }

    public static function validar($datos)
    {

      
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
                } else {
                    echo "Token invalido, token vencido";
                }
            } else {
                echo "Token invalido";
            }
        }
    }
}
