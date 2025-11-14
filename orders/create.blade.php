<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Crear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('orders.index') }}">Gestión</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Estudiantes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('orders.index') }}">Pedidos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-primary">Crear Pedido</h1>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" class="card shadow-sm">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="order_number" class="form-label">Número de Pedido</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="{{ old('order_number') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="order_date" class="form-label">Fecha del Pedido</label>
                        <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="customer_email" class="form-label">Email del Cliente</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="total_amount" class="form-label">Monto Total</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Seleccione...</option>
                            <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>pending</option>
                            <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>completed</option>
                            <option value="cancelled" {{ old('status')=='cancelled' ? 'selected' : '' }}>cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-danger">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
