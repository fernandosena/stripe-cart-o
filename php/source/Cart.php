<?php
session_start();
// Função para adicionar um produto ao carrinho
function adicionarProduto($id, $type, $nome, $preco, $session_name = "carrinho", $redirect = "cart.php") {
    $produto = array(
        'id' => $id,
        'tipo' => $type,
        'nome' => $nome,
        'preco' => $preco
    );

    // Verifica se o produto já existe no carrinho
    $indice = array_search($id, array_column($_SESSION[$session_name], 'id'));
    if ($indice !== false) {
        // Se já existe, atualiza a quantidade
        $_SESSION[$session_name][$indice]['quantidade']++;
    } else {
        // Se não existe, adiciona o produto ao carrinho
        $produto['quantidade'] = 1;
        $_SESSION[$session_name][] = $produto;
    }
    header('Location: '.$redirect);
}

// Função para remover um produto do carrinho
function removerProduto($id, $session_name = "carrinho", $redirect = "cart.php") {
    $indice = array_search($id, array_column($_SESSION[$session_name], 'id'));
    if ($indice !== false) {
        unset($_SESSION[$session_name][$indice]);
        $_SESSION[$session_name] = array_values($_SESSION[$session_name]); // Reindexa o array
    }
    header('Location: '.$redirect);
}

// Função para calcular o valor total do carrinho
function calcularTotal($session_name = "carrinho") {
    $total = 0;
    foreach ($_SESSION[$session_name] as $produto) {
        $total += $produto['preco'] * $produto['quantidade'];
    }
    return $total;
}

// Exemplo de uso das funções:
// adicionarProduto(1, 'Produto A', 10.00);
// adicionarProduto(2, 'Produto B', 15.00);
// removerProduto(1);
