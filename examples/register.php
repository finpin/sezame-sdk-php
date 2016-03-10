<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$client = new \SezameLib\Client();

$registerRequest = $client->register()->setEmail('reg@bretterklieber.com')->setName('xtest remove');

try {

    $registerResponse = $registerRequest->send();

    $clientcode   = $registerResponse->getClientCode();
    $sharedsecret = $registerResponse->getSharedSecret();

    printf("%s %s\n", $clientcode, $sharedsecret);

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

print( $registerRequest->getClient()->getRequest() );
print( $registerRequest->getClient()->getResponse() );

