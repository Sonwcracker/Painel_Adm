<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login do Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="../include/login.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-1 bg-body-tertiary">
    <main class="w-100 m-auto form-cointainer">
        <form action="processa_login.php" method="post">
            <img src="../Imagens/Logotipo_bravo.png" class="mb-1 px-1 logo" height="250" width="225" alt="">
            <h1 class="h3 mb-3 fw-normal">Login Administrador</h1>
            <div class="form-floating">
                <input type="text" name="nome" class="form-control" id="floatingInput" placeholder="Usuario Administrador" required>
                <label for="floatingInput">Usuario</label>
            </div>
            <div class="form-floating">
                <input type="password" name="senha" class="form-control" id="floatingInput" placeholder="Senha" required>
                <label for="floatingInput">Senha</label>
            </div>
            <div class="form-check text-start my-3">
                <input type="checkbox" class="form-check-input" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">Lembre de mim</label>
            </div>
            <button class="btn btn-primary w-100 py-2">Entrar</button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>