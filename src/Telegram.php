<?php

/**
 * Class create by AlexR1712
 */
print(realpath('.'));
class Telegram
{
    private $token;
    private $webhook;
    private $apiUrl;
    
    public function __construct($pwrtelegram = false)
    {
        /**
         * Can use PWRTelegram for active more power of telegram
         */
        $config = parse_ini_file("bot.config");
        $this->apiUrl = ($pwrtelegram) ? 'https://api.pwrtelegram.xyz/bot'.$config['BOT_TOKEN'].'/' : 'https://api.telegram.org/bot'.$config['BOT_TOKEN'].'/';
    }


/**
 * @param string $method
 */
public function apiRequestWebhook($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");

        return false;
    }

    $parameters['method'] = $method;
    header('Content-Type: application/json');
    echo json_encode($parameters);

    return true;
}

/**
 * @param resource $handle
 */
public function exec_curl_request($handle)
{
    $response = curl_exec($handle);
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl retornó un error $errno: $error\n");
        curl_close($handle);

        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
    if ($http_code >= 500) {

        // do not wat to DDOS server if something goes wrong

        sleep(10);

        return false;
    } elseif ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("La solicitud falló con el error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('El token provisto es inválido');
        }

        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("La solicitud fue exitosa: {$response['description']}\n");
        }

        $response = $response['result'];
    }

    return $response;
}

/**
 * @param string $method
 */
public function apiRequest($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");

        return false;
    }

    foreach ($parameters as $key => &$val) {

        // encoding to JSON array parameters, for example reply_markup

        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }

    $url = $this->apiUrl.$method.'?'.http_build_query($parameters);
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    return self::exec_curl_request($handle);
}

/**
 * @param string $method
 */
public function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("El nombre del método debe ser una cadena de texto\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Los parámetros deben ser un arreglo/matriz\n");

        return false;
    }

    $parameters['method'] = $method;
    $handle = curl_init($this->apiUrl);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    return exec_curl_request($handle);
}


public function sendMessage($chat_id, $text, $args = [] )
{
    return self::apiRequest('sendMessage', [
        'chat_id' => $chat_id, 
        'text' => $text
    ]);
}


public function test()
{
    print("hola");
    print($this->apiUrl);
}

}
