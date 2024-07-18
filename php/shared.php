<?php
  require 'vendor/autoload.php';
  if(!file_exists('.env')): 
?>
  <h1>Arquivo <code>.env</code> não econtrado</h1>
<?php 
exit; endif; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();
if (!$_ENV['STRIPE_SECRET_KEY']):
?>
  <h1><code>STRIPE_SECRET_KEY</code> Inválido</h1>
<?php
exit;endif;

$stripe = new \Stripe\StripeClient([
  'api_key' => $_ENV['STRIPE_SECRET_KEY']
]);