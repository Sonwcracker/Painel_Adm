<?php
session_start();
require_once('../conexao/conexao.php');

include_once('../include/nav.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:../login/login.php");
    exit();
}

$categoria_id = $_GET['id'];

// Busca as informações da categoria.
$stmt_cate = $pdo->prepare("SELECT * FROM CATEGORIA WHERE CATEGORIA_ID = :categoria_id");
$stmt_cate->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
$stmt_cate->execute();
$cate = $stmt_cate->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Atualizando as informações da categoria.
    $categoria_nome = $_POST['categoria_nome'];
    $categoria_desc = $_POST['categoria_desc'];
    $categoria_ativo = isset($_POST['categoria_ativo']) ? 1 : 0;

    try {
        $stmt_update_cate = $pdo->prepare("UPDATE CATEGORIA SET CATEGORIA_NOME = :categoria_nome, CATEGORIA_DESC = :categoria_desc, CATEGORIA_ATIVO = :categoria_ativo WHERE CATEGORIA_ID = :categoria_id");
        $stmt_update_cate->bindParam(':categoria_nome', $categoria_nome);
        $stmt_update_cate->bindParam(':categoria_desc', $categoria_desc);
        $stmt_update_cate->bindParam(':categoria_ativo', $categoria_ativo, PDO::PARAM_BOOL);
        $stmt_update_cate->bindParam(':categoria_id', $categoria_id);
        $stmt_update_cate->execute();

        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
                    myModal.show();
                });
              </script>';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar categoria: " . $e->getMessage() . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../include/editar_categoria.css">
</head>

<body>
    <div class="mt-3">
        <h1 class="display-4 py-2">Editar Categoria</h1>
    </div>
    <div class="container bg-dark align-center">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="categoria_nome">Nome:</label>
            <input type="text" name="categoria_nome" id="categoria_nome" value="<?= $cate['CATEGORIA_NOME'] ?>"
                required>
            <p>
                <label for="categoria_desc">Descrição:</label>
                <input type="text" name="categoria_desc" id="categoria_desc" value="<?= $cate['CATEGORIA_DESC'] ?>"
                    required>
            <p>
                <label for="categoria_ativo">Ativo:</label>
                <input type="checkbox" name="categoria_ativo" id="categoria_ativo" value="1" <?= $cate['CATEGORIA_ATIVO'] ? 'checked' : '' ?>>
            <p>

                <button onclick="myFunction()" type="submit" class="btn btn-outline-light" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">Atualizar Categoria</button>

            <div class="modal" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atenção</h5>
                        </div>
                        <div class="modal-body">
                            <p>Sua Categoria Foi Atualizada Com Sucesso!!!</p>
                        </div>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal"
                                aria-label="Close">Fechar</button>
                            <a href="categoria.php" class="btn btn-outline-light">Lista de Categorias</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>