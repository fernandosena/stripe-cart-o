<?php
if(empty($_GET["title"]) || empty($_GET["price"])){
  ?>
      <h1>Volte a <a href="plans.php">página de planos</a> e escolha um plano</h1>
  <?php
  die();  
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Assinatura</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/css/base.css" />
    <script src="https://js.stripe.com/v3/"></script>

    <script src="js/utils.js" defer></script>
    <script src="js/subscribe.js" defer></script>
  </head>
  <body>
    <main>
      <a href="index.php">Home</a>
      <h1>Assinatura</h1>
      <p>
        <h4><a href="https://stripe.com/docs/testing#cards" target="_blank">Cartões de teste</a>:</h4>
        <div>
          <code>4242424242424242</code> (Visa)
        </div>
        <div>
          <code>5555555555554444</code> (Mastercard)
        </div>
      </p>

      <p>
        Use qualquer vencimento futuro, qualquer CVC de 3 dígitos e qualquer código postal.
      </p>
      <form id="payment-form">
      <div class="mb-3">
          <label for="name">Nome completo</label>
          <input type="text" class="form-control" id="name" placeholder="Nome completo" value="Fernando Sena" required="">
        </div>

        <div class="mb-3">
          <label for="email">E-mail</label>
          <input type="email" value="you@example.com" class="form-control" id="email" placeholder="you@example.com">
        </div>
        <div class="mb-3">
          <label for="phone">Telefone</label>
          <input type="phone" value="11999999999" class="form-control" id="phone" placeholder="11999999999">
        </div>

        <div class="mb-3">
          <label for="address">Endereço</label>
          <input type="text" class="form-control" value="1234 Main St" id="address" placeholder="1234 Main St" required="">
        </div>

        <div class="mb-3">
          <label for="address2">Address 2</label>
          <input type="text" class="form-control" id="address2" placeholder="Apartment or suite" value="Suite">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="country">Pais</label>
            <select class="custom-select d-block w-100" id="country" required="">
              <option value="br">Brasil</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="state">Estado</label>
            <select class="custom-select d-block w-100" id="state" required="">
              <option value="São Paulo">São Paulo</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="state">Cidade</label>
            <select class="custom-select d-block w-100" id="city" required="">
              <option value="São Paulo">São Paulo</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="zip">CEP</label>
            <input type="text" class="form-control" id="zip" placeholder="" required="" value="05207130">
          </div>
        </div>
        <input type="hidden" id="title" name="title" value="<?= ($_GET["title"] ?? null)?>">
        <input type="hidden" id="price" name="price" value="<?= ($_GET["price"] ?? null)?>">
        <hr class="mb-4">
        <div id="payment-element">
          <!-- Elements will create form elements here -->
        </div>
        <button id="submit">Submit</button>
        <div id="error-message">
          <!-- Display error message to your customers here -->
        </div>
      </form>
      <div id="messages" role="alert" style="display: none;"></div>
    </main>
  </body>
</html>
