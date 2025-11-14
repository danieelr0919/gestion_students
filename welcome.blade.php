<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Gestión</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Estudiantes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-6 fw-semibold text-primary">Bienvenido al Sistema de Gestión</h1>
            <p class="lead text-muted mb-0">Accede rápidamente a los módulos disponibles</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Estudiantes</h5>
                        <p class="card-text text-muted">Administra estudiantes: crea, edita, elimina y consulta detalles.</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('students.index') }}" class="btn btn-primary">Ir a Estudiantes</a>
                            <a href="{{ route('students.create') }}" class="btn btn-outline-primary">Nuevo Estudiante</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Productos</h5>
                        <p class="card-text text-muted">Administra productos: listado, creación, edición y detalle.</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-success">Ir a Productos</a>
                            <a href="{{ route('products.create') }}" class="btn btn-outline-success">Nuevo Producto</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Pedidos</h5>
                        <p class="card-text text-muted">Administra pedidos: listado, creación, edición y detalle.</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-info">Ir a Pedidos</a>
                            <a href="{{ route('orders.create') }}" class="btn btn-outline-info">Nuevo Pedido</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container text-center">
            <p class="mb-0">Sistema de Gestión &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>