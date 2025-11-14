<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('students.index') }}">Gestión</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('students.index') }}">Estudiantes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1 class="text-primary">Registrar Nuevo Estudiante</h1>

        <a href="{{ route('students.index') }}" class="btn btn-secondary">Volver a la lista de estudiantes</a>
        
        <!-- Mostrar errores de validacion -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Campo Codigo -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_code" class="form-label">Codigo del Estudiante</label>
                            <input type="text" class="form-control" id="student_code" name="student_code" value="{{ old('student_code') }}" required>
                            <div class="form-text">Codigo Unico del Estudiante</div>
                        </div>

                        <!-- Campo Nombre -->

                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            <div class="form-text">Nombre del Estudiante</div>
                        </div>

                        <!-- Campo Apellido -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ old('last_name') }}" required>
                        </div>
                        <!-- Campo Email-->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            <div class="form-text">Email del Estudiante</div>
                        </div>
                    </div>
                    <!-- Campo Telefono-->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                            <div class="form-text">Telefono del Estudiante</div>
                        </div>
                    </div>

                    <!-- Direccion -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Direccion</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                            <div class="form-text">Direccion del Estudiante</div>
                        </div>

                    <!-- Campo Carrera-->
                        <div class="mb-3">
                            <label for="career" class="form-label">Carrera</label>
                            <input type="text" class="form-control" id="career" name="career" value="{{ old('career') }}" required>
                            <div class="form-text">Carrera del Estudiante</div>
                        </div>

                    <!-- Campo Año de Matrícula-->
                        <div class="mb-3">
                            <label for="enrollment_year" class="form-label">Año de Matrícula</label>
                            <input type="number" class="form-control" id="enrollment_year" name="enrollment_year" value="{{ old('enrollment_year', date('Y')) }}"  required>
                            <div class="form-text">Año de Matrícula del Estudiante</div>
                        </div>

                    <!-- Campo Foto-->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif" value="{{ old('photo') }}" required>
                            <div class="form-text">Foto del Estudiante</div>
                        </div>
                    </div>
                </div>

                <!--Botones-->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success btn-lg">Guardar Estudiante</button>
                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>

        <footer class="bg-dark text-center text-white mt-4 p-3">
            <div class="container text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Sistema de Estudiantes</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

            
    
</body>
</html>