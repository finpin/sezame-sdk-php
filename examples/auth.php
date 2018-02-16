<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$certfile = __DIR__ . '/cert/test-cert.pem';
$keyfile = __DIR__ . '/cert/test-key.pem';

$certfile = __DIR__ . '/cert/cert.pem';
$keyfile = __DIR__ . '/cert/key.pem';


$timeout = 20;

$client = new \SezameLib\Client($certfile, $keyfile);

$authRequest = $client->authorize()->setUsername('johndoe');

try {

    $authResponse = $authRequest->send();

    if ($authResponse->isNotfound()) {
        print "User was not found\n";
        die;
    }

    print($authRequest->getClient()->getRequest());
    print($authRequest->getClient()->getResponse());


    if ($authResponse->isOk())
    {
        $statusRequest = $client->status()->setAuthId($authResponse->getId());

        for ($i = 0; $i < $timeout; $i++)
        {

            $statusResponse = $statusRequest->send();
            print($statusRequest->getClient()->getRequest());
            print($statusRequest->getClient()->getResponse());

            if ($statusResponse->isAuthorized())
            {
                printf("request has been authorized by user\n");
                die;
            }

            if ($statusResponse->isDenied()) {
                printf("request has been denied user\n");
                die;
            }

            sleep(1);
        }

        printf("user did not respond within %d seconds\n", $timeout);
        die;
    }


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
