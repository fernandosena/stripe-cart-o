<?php
$arquivo = "webhook.txt";
file_put_contents($arquivo, '');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cartão</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/css/base.css" />
  </head>
  <body>
    <main>
      <a href="index.php">Home</a>
      <p>Arquivo do historico do webhook limpo</p>
    </main>
  </body>
</html>
