<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$certfile = __DIR__ . '/cert/test-cert.pem';
$keyfile = __DIR__ . '/cert/test-key.pem';

$certfile = __DIR__ . '/cert/cert.pem';
$keyfile = __DIR__ . '/cert/key.pem';


$client = new \SezameLib\Client($certfile, $keyfile);

$username = 'johndoe';

try {

    $statusRequest = $client->linkStatus();
    $statusResponse = $statusRequest->setUsername($username)->send();

    print($statusRequest->getClient()->getRequest());
    print($statusRequest->getClient()->getResponse());

    if ($statusResponse->isLinked()) {
        print "user already has been linked\n";
        die;
    }

    $linkRequest = $client->link();
    $linkResponse = $linkRequest->setUsername($username)->send();


    if ($linkResponse->isDuplicate()) {
        print "user already has been linked\n";
        die;
    }

    $qrCode = $linkResponse->getQrCode($username);
    $qrCode->setSize(300)->setLabelMargin([
        't' => 10,
        'r' => 10,
        'b' => 10,
        'l' => 10,
    ]); // optionally adjust qrcode dimensions

    printf('<img src="%s"/>', $qrCode->writeString(\Endroid\QrCode\Writer\PngDataUriWriter::class));


    file_put_contents('qrcode.html', sprintf('<img src="%s"/>', $qrCode->writeString(\Endroid\QrCode\Writer\PngDataUriWriter::class)));

    file_put_contents('qrcode.eps', $qrCode->writeString(\Endroid\QrCode\Writer\EpsWriter::class));

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

print($linkRequest->getClient()->getRequest());
print($linkRequest->getClient()->getResponse());