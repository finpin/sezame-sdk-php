<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

$clientcode = '5668193249daf5.23068421'; // optained by register call
$sharedsecret = 'a91d95a323ce23c4c53625984371e5a332907cdc1c1d8eb23dc01c508f669491'; // optained by register call

$client = new \SezameLib\Client();

$privateKeyPassword = null;

$csrKey = $client->makeCsr($clientcode, 'michael@bretterklieber.com', $privateKeyPassword,
                           Array(
                               'countryName'            => 'AT',
                               'stateOrProvinceName'    => 'Vienna',
                               'localityName'           => 'Vienna',
                               'organizationName'       => '-',
                               'organizationalUnitName' => '-'
                           ));

$signRequest = $client->sign()->setCSR($csrKey->csr)->setSharedSecret($sharedsecret);

try {

    $signResponse = $signRequest->send();

    $cert = $signResponse->getCertificate();

    printf("CSR:\n%s\n\n", $csrKey->csr);
    printf("Certificate:\n%s\n\n", $cert);
    printf("Private Key:\n%s\n\n", $csrKey->key);

} catch (\SezameLib\Exception\Connection $e) {
    // connection to hq failed
    printf("Connection failure: %s %d\n", $e->getMessage(), $e->getCode());
} catch (\SezameLib\Exception\Parameter $e) {
    // wrong or missing parameter provided
    printf("Parameter failure: %s %d\n", $e->getMessage(), $e->getCode());
    print_r($e->getErrorInfo());
} catch (\SezameLib\Exception\Response $e) {
    // hq returned unexpected response
    printf("%s %d\n", $e->getMessage(), $e->getCode());
}

print $signRequest->getClient()->getRequest();
print $signRequest->getClient()->getResponse();
