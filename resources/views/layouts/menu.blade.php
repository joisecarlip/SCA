<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SCA')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>

        aside {
            width: 300px;
            background-color: #007BFF;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            overflow-y: auto; 
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        aside::-webkit-scrollbar {
            display: none;
        }

        aside a {
            color: #fff;
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: background-color 0.3s;
        }

        aside a i {
            font-size: 1.8em; 
        }

        aside a:hover {
            background-color: #0056b3;
        }

        aside .perfil-img {
            width: 100%;
            border-radius: 50%;
            margin: -90px auto -90px;
            display: block;
        }


        main {
            margin-left: 320px; 
            padding: 20px;
        }
    </style>
</head>
<body >
    <aside>
        
        <nav>
            <ul class="list-unstyled">
                @if ($user->user_tipo == '1')
                <li>
                    <a href="#">
                        <i class="bx bx-home-alt-2"></i> Inicio
                    </a>
                </li>
                <li><a href="#"><i class="bx bx-male"></i> Estudiantes</a></li>

                @else

                <li>
                    <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#usuariosMenu" aria-expanded="false"><i class="bx bx-user-plus"></i> Gestión de Usuarios</a>
                    <ul id="usuariosMenu" class="list-unstyled collapse">
                        <li><a href="{{ url('/usuarios') }}">Usuarios</a></li>
                    </ul>
                </li>

                @endif

                <li><a href="{{ url('/') }}"><i class="bx bx-log-out"></i> Salir</a></li>
            </ul>
        </nav>
    </aside>

    <main>
    <div id="app">
        @yield('content')
    </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</script>
</body>
</html>
