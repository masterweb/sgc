<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = $_POST["user"];
$pss = $_POST["pass"];
$numero = substr($_POST["numero"], 1);
$mensaje = $_POST["mensaje"];

echo resultado_ws($user, $pss, $numero, $mensaje);

function resultado_ws($user, $pss, $numero, $mensaje) {
    /**
     * Define POST URL and also payload
     */
    $mensajef = str_replace(" ", "+", $mensaje);

    $url = 'http://soporte.innobix.com.ec/sapi/sms_sendingp.php';

    define('XML_POST_URL', $url);

    $fields = array(
        'user' => 'kiaec',
        'pss' => 'ZXD456GKi4@20L6',
        'numero' => $numero,
        'mensaje' => $mensajef,
    );
    
    $fields_string = '';

    //url-ify the data for the POST
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }

    rtrim($fields_string, '&');

    /**
     * Initialize handle and set options
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, XML_POST_URL);
    //curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
    /**
     * Execute the request and also time the transaction
     */
    $start = array_sum(explode(' ', microtime()));
    $result = curl_exec($ch);
    $stop = array_sum(explode(' ', microtime()));
    $totalTime = $stop - $start;

    /**
     * Check for errors
     */
    if (curl_errno($ch)) {
        $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
    } else {
        $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch ($returnCode) {
            case 404:
                $result = 'ERROR -> 404 Not Found';
                break;
            default:
                break;
        }
    }

    /**
     * Close the handle
     */
    $time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
    $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //var_dump($result);
    curl_close($ch);

    /**
     * Output the results and time
     */
    //echo 'Total time for request: ' . $totalTime . "\n";
    //echo "time: ". ($time);
    //echo "header: ". ($header);   
    return $result;

    /**
     * Exit the script
     */
    //exit(0);
}

?>
