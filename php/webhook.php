<?php
require_once 'shared.php';
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$body = json_decode($input);
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $input,
    $_SERVER['HTTP_STRIPE_SIGNATURE'],
    $_ENV['STRIPE_WEBHOOK_SECRET'],
  );
}
catch (Exception $e) {
  http_response_code(403);
  echo json_encode([ 'error' => $e->getMessage() ]);
  exit;
}

$nomeDoArquivo = "webhook.txt";

$arquivo = fopen($nomeDoArquivo, "a"); // Abre o arquivo para escrita
if ($arquivo) {
    if ($event->type == 'payment_intent.succeeded') {
      error_log('💰 Pagamento recebido');
      $jsonData = json_encode($event, JSON_PRETTY_PRINT);
      if ($jsonData !== false) {
          fwrite($arquivo, $jsonData . "\n"); // Escreve os dados no arquivo
          echo "Evento de pagamento salvo com sucesso no arquivo $nomeDoArquivo.\n";
      } else {
          error_log("Erro ao converter evento para JSON: " . json_last_error_msg());
      }
    }
    else if ($event->type == 'payment_intent.payment_failed') {
      error_log('❌ Pagamento Falhou.');
      $jsonData = json_encode($event, JSON_PRETTY_PRINT);
      if ($jsonData !== false) {
          fwrite($arquivo, $jsonData . "\n"); // Escreve os dados no arquivo
          echo "Evento de pagamento salvo com sucesso no arquivo $nomeDoArquivo.\n";
      } else {
          error_log("Erro ao converter evento para JSON: " . json_last_error_msg());
      }
    }
    fclose($arquivo); // Fecha o arquivo
    echo "Dados gravados com sucesso no arquivo $nomeDoArquivo.";
} else {
    echo "Erro ao abrir o arquivo $nomeDoArquivo.";
}

echo json_encode(['status' => 'success']);