<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$certfile = __DIR__ . '/cert/test-cert.pem';
$keyfile = __DIR__ . '/cert/test-key.pem';

$client = new \SezameLib\Client($certfile, $keyfile);

// simulate wrong client cert
//$client = new \SezameLib\Client('xxx', 'aaa');

try {
    //$client->linkStatus()->send();

    $authRequest = $client->authorize();
    $authRequest
        ->setUsername('')
        ->setMessage('Login to Example')
        ->setTimeout(10000)
        ->setType('foo')
        ->setCallback('http://test.example.com/sezameauthcallback/');

    $authRequest->send();

} catch (\SezameLib\Exception\Connection $e) {
    // connection to hq failed
    printf("Connection failure: %s %d\n", $e->getMessage(), $e->getCode());
} catch (\SezameLib\Exception\Parameter $e) {
    // wrong or missing parameter provided
    print_r($e->getErrorInfo());
} catch (\SezameLib\Exception\Response $e) {
    // hq returned unexpected response
    printf("%s %d\n", $e->getMessage(), $e->getCode());
}


