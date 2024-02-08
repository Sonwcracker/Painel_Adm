<!doctype html>
<html lang="en">

<script>

    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('search-input');
        var searchButton = document.getElementById('search-button');
        var tableRows = document.querySelectorAll('tbody tr');

        searchButton.addEventListener('click', function () {
            searchTable();
        });

        searchInput.addEventListener('input', function () {
            searchTable();
        });

        function searchTable() {
            var searchTerm = searchInput.value.trim().toLowerCase();

            tableRows.forEach(function (row) {
                var rowData = Array.from(row.children).map(function (cell) {
                    return cell.textContent.toLowerCase();
                });

                var shouldShow = rowData.some(function (data) {
                    return data.includes(searchTerm);
                });

                row.style.display = shouldShow || searchTerm === '' ? '' : 'none';
            });
        }
    });


</script>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      
        .navbar {
            background-color: #000000;
            color: #ffffff;
        }

        .dropdown-menu .dropdown-item {
            color: #ffffff;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #303434;
            border-radius: 5%;
            color: #ffffff;
        }
        
        #search-button{
            margin: 0px;
            border: none;
            padding: 0px;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-1 mb-1 px-2">
        <div class="container-fluid px-1">
            <a class="navbar-brand py-1" href="#"><img src="../Imagens/Logotipo_bravo.png" height="75px" width="75px"
                    alt="Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Bravo Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="painel_admin.php">Painel de Admin</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Produtos
                        </a>
                        <ul class="dropdown-menu bg-dark">
                            <li><a class="dropdown-item" href="cadastrar_produto.php">Cadastrar Produto</a></li>
                            <li><a class="dropdown-item" href="listar_produtos.php">Lista de Produtos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Administradores
                        </a>
                        <ul class="dropdown-menu bg-dark">
                            <li><a class="dropdown-item" href="cadastrar_admin.php">Cadastrar Admin</a></li>
                            <li><a class="dropdown-item" href="listar_admin.php">Lista de Administradores</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex me-2" role="search">
                    <input id="search-input" class="form-control me-2" type="search" placeholder="O que você procura?" aria-label="Search">
                    <button id="search-button" class="" type="submit"></button>
                </form>
                <a href="login.php" class="btn btn-outline-danger md-2" tabindex="-1" role="button"
                    aria-disabled="true">Sair</a>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>