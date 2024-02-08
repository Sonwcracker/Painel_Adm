<?php
// Inicia a sessão para gerenciamento do usuário.
session_start();

// Importa a configuração de conexão com o banco de dados.
require_once('../conexao/conexao.php');

include_once('../include/nav2.php');

// Verifica se o administrador está logado.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:../login/login.php");
    exit();
}

// Bloco que será executado quando o formulário for submetido.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do POST.
    $nome = $_POST['adm_nome'];
    $email = $_POST['adm_email'];
    $senha = $_POST['adm_senha'];
    $ativo = isset($_POST['adm_ativo']) ? 1 : 0;

    // Inserindo produto no banco.
    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES (:adm_nome, :adm_email, :adm_senha, :adm_ativo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':adm_nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':adm_email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':adm_senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':adm_ativo', $ativo, PDO::PARAM_INT);
        $stmt->execute();

        // Pegando o ID do produto inserido.
        $ADM_ID = $pdo->lastInsertId();

        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("confirmModal"));
            myModal.show();
        });
      </script>';
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar Administrador: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Admin</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../include/cadastrar_admin.css">
</head>

<body>
    <div class="mt-3">
        <h1 class="display-4 py-2">Cadastrar Administrador</h1>
    </div>
    <div class="container bg-dark">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Campos do formulário para inserir informações do produto -->
            <label for="adm_nome">Nome:</label>
            <input type="text" name="adm_nome" id="adm_nome" required>
            <p>
                <label for="adm_email">Email</label>
                <input type="text" name="adm_email" id="adm_email" required>
            <p>
                <label for="adm_senha">Senha:</label>
                <input type="text" name="adm_senha" id="adm_senha" required>
            <p>
                <label for="adm_ativo">Ativo:</label>
                <input type="checkbox" name="adm_ativo" id="adm_ativo" value="1" checked>
            <p>
                <button type="submit" class="btn btn-large btn-outline-light" data-dismiss="modal">
                    Cadastrar Administrador
                </button>

            <div class="modal" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atenção</h5>
                        </div>
                        <div class="modal-body">
                            <p>Seu Administrador Foi Cadastrado Com Sucesso!!!</p>
                        </div>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-large btn-outline-light" data-dismiss="modal" aria-label="Close">Fechar</button>
                            <a href="listar_admin.php" class="btn btn-outline-light">Lista de Administradores</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</body>

</html>