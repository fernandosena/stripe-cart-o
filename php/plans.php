<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tipos</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/base.css" />
  </head>
  <body>
    

<div class="container">
    <h1 class="text-center mb-5">Assinaturas</h1>
    <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
            <h4 class="my-0 font-weight-normal">Pro</h4>
            </div>
            <div class="card-body">
            <h1 class="card-title pricing-card-title">R$ 15,00 <small class="text-muted">/ mês</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
                <li>20 usuários</li>
                <li>10 GB de armazenamento</li>
                <li>Suporte por email prioritário</li>
                <li>Acesso ao centro de ajuda</li>
            </ul>

            <form action="cart.php">
                <button type="submit" style="color: #fff" class="btn btn-primary w-100">Contate-nos</button>
                <input type="hidden" name="id" value="4">
                <input type="hidden" name="type" value="plan">
                <input type="hidden" name="title" value="Pro">
                <input type="hidden" name="price" value="15">
            </form>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
            <h4 class="my-0 font-weight-normal">Premium</h4>
            </div>
            <div class="card-body">
            <h1 class="card-title pricing-card-title">R$ 29 <small class="text-muted">/ mês</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
                <li>30 usuários</li>
                <li>15 GB de armazenamento</li>
                <li>Suporte por email e telefone</li>
                <li>Acesso ao centro de ajuda</li>
            </ul>
            <form action="cart.php">
                <button type="submit" style="color: #fff" class="btn btn-primary w-100">Contate-nos</button>
                <input type="hidden" name="id" value="5">
                <input type="hidden" name="type" value="plan">
                <input type="hidden" name="title" value="Premium">
                <input type="hidden" name="price" value="29">
            </form>
            </div>
        </div>
    </div>
  </body>
</html>
