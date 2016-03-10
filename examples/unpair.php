<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$certfile = __DIR__ . '/cert/test-cert.pem';
$keyfile = __DIR__ . '/cert/test-key.pem';

$client = new \SezameLib\Client($certfile, $keyfile);

$username = 'foo-client-user';

try {

    $deleteRequest = $client->linkDelete();
    $deleteResponse = $deleteRequest->setUsername($username)->send();



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

print($deleteRequest->getClient()->getRequest());
print($deleteRequest->getClient()->getResponse());