<?php


require_once __DIR__ . "/autoload.php";
use CoinbaseCommerce\Webhook;

include('../connect.php');

$secret = SECRET_KEY_WEBHOOK_COINBASE_COMMERCE;

$headerName = 'x-cc-webhook-signature';

$signraturHeader;

foreach (getallheaders() as $nombre => $valor) {
    
    if (trim(strtolower($nombre)) == $headerName)
    {
        $signraturHeader = $valor;
        break;
    }
}

$payload = trim(file_get_contents('php://input'));

try {
    $event = Webhook::buildEvent($payload, $signraturHeader, $secret);
    
    http_response_code(200);
    
    $searchPayment = $pdo->prepare('SELECT COUNT(*) FROM Infecteds WHERE Payment_Code = ?');
    $searchPayment->execute([$event->data->code]);
    
    if ($searchPayment->fetchColumn() > 0)
    {
        $updateStatusPayment = $pdo->prepare('UPDATE Infecteds SET Payment_Status = ? WHERE Payment_Code = ?');
        $updateStatusPayment->execute([$event->type, $event->data->code]);
    }
    
    //echo sprintf('Successully verified event with id %s and type %s.', $event->id, $event->type);
} catch (\Exception $exception) {
    http_response_code(400);
    echo 'Error occured. ' . $exception->getMessage();
}


?>