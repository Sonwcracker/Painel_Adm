<?php
session_start(); // Iniciar a sessão

if (!isset($_SESSION['admin_logado'])) {
    header('Location: ../login/login.php');
    exit();
}
include_once('../include/nav2.php');
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../include/painel_admin.css">
</head>

<body class="align-items-center bg-dark justify-content-center">
    <div class="container mt-5 pt-5">
        <div class="row mt-5">
            <div class="col-md-8 offset-md-2">
                <h1 class="rgb-border pt-3">Bem-vindo, Administrador!</h1>
            </div>
        </div>
    </div>
    <div class="container-fluid text-center w-100 mt-5">
        <div class="row g-0 text-center">
            <div class="col-lg-2 col-md-6 col-sm-6 py-5 px-5 mx-5">
                <!-- Card Lista de Produtos -->
                <div class="card" style="width: 18rem;">
                    <img src="../Imagens/lista_produto.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Lista de Produtos</h5>
                        <p class="card-text">Veja uma lista completa com todos os produtos já cadastrados</p>
                        <a href="../produto/listar_produtos.php" class="btn btn-dark">Ver Lista</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 py-5 px-5 mx-5">
                <!-- Card Lista de Administradores -->
                <div class="card" style="width: 18rem;">
                    <img src="../Imagens/admin.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Lista de Administradores</h5>
                        <p class="card-text">Veja uma lista completa com todos os Administradores já cadastrados</p>
                        <a href="../administrador/listar_admin.php" class="btn btn-dark">Ver Lista</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 py-5 px-5 mx-5">
                <!-- Card Cadastrar Produtos -->
                <div class="card" style="width: 18rem;">
                    <img src="../Imagens/cadastrar_produ.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Cadastrar Produtos</h5>
                        <p class="card-text">Cadastre todos os nossos novos produtos aqui</p>
                        <a href="../produto/cadastrar_produto.php" class="btn btn-dark">Cadastrar</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 py-5 px-5 mx-5 ">
                <!-- Card Cadastrar Administrador -->
                <div class="card" style="width: 18rem;">
                    <img src="../Imagens/cadastrar_adm.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Cadastrar Administrador</h5>
                        <p class="card-text">Cadastre todos os nossos novos administradores aqui</p>
                        <a href="../administrador/cadastrar_admin.php" class="btn btn-dark">Cadastrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>