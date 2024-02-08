<?php
session_start();
require_once('../conexao/conexao.php');

include_once('../include/nav.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: ../login/login.php");
    exit();
}

$adm_id = $_GET['id'];

// Busca as informações do administrador.
$stmt_admin = $pdo->prepare("SELECT * FROM ADMINISTRADOR WHERE ADM_ID = :adm_id");
$stmt_admin->bindParam(':adm_id', $adm_id, PDO::PARAM_INT);
$stmt_admin->execute();
$admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Atualizando as informações do administrador.
    $adm_nome = $_POST['adm_nome'];
    $adm_email = $_POST['adm_email'];
    $adm_senha = $_POST['adm_senha'];
    $adm_ativo = isset($_POST['adm_ativo']) ? 1 : 0;

    try {
        $stmt_update_admin = $pdo->prepare("UPDATE ADMINISTRADOR SET ADM_NOME = :adm_nome, ADM_EMAIL = :adm_email, ADM_SENHA = :adm_senha, ADM_ATIVO = :adm_ativo WHERE ADM_ID = :adm_id");
        $stmt_update_admin->bindParam(':adm_nome', $adm_nome);
        $stmt_update_admin->bindParam(':adm_email', $adm_email);
        $stmt_update_admin->bindParam(':adm_senha', $adm_senha);
        $stmt_update_admin->bindParam(':adm_ativo', $adm_ativo);
        $stmt_update_admin->bindParam(':adm_id', $adm_id);
        $stmt_update_admin->execute();

        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
                    myModal.show();
                });
              </script>';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao atualizar administrador: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Administrador</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../include/editar_admin.css">
</head>

<body>
    <div class="mt-3">
        <h1 class="display-4 py-2">Editar Administrador</h1>
    </div>
    <div class="container bg-dark align-center">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="adm_nome">Nome:</label>
            <input type="text" name="adm_nome" id="adm_nome" value="<?= $admin['ADM_NOME'] ?>" required>
            <p>
                <label for="adm_email">Email:</label>
                <input type="text" name="adm_email" id="adm_email" value="<?= $admin['ADM_EMAIL'] ?>" required>
            <p>
                <label for="adm_senha">senha:</label>
                <input type="password" name="adm_senha" id="adm_senha" value="<?= $admin['ADM_SENHA'] ?>" required>
            <p>
                <label for="adm_ativo">Ativo:</label>
                <input type="checkbox" name="adm_ativo" id="adm_ativo" value="1" <?= $admin['ADM_ATIVO'] ? 'checked' : '' ?>>
            <p>

                <button onclick="myFunction()" type="submit" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">Atualizar Administrador</button>

            <div class="modal" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atenção</h5>
                        </div>
                        <div class="modal-body">
                            <p>Seu Administrador Foi Atualizado Com Sucesso!!!</p>
                        </div>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal"
                                aria-label="Close">Fechar</button>
                            <a href="listar_admin.php" class="btn btn-outline-light">Lista de Administradores</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>