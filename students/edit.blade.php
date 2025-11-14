<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Estudiante - {{ $student->first_name }} {{ $student->last_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .photo-preview {
            max-width: 200px;
            max-height: 200px;
            border: 3px solid #dee2e6;
            border-radius: 5px;
            margin: 10px;
        }

        .current-photo {
            border: 3px solid #28a745;
        }

        .form-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
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
        <h1 class="text-warning">Editar Estudiante</h1>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('students.index') }}" class="btn btn-primary">Volver a la lista de estudiantes</a>
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info">Ver detalles</a>
        </div>
        <!-- Mostrar errores de validacion -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <h5>❌ Por favor corrige los siguientes errores:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Mostrar mensajes de exito -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!--Seccion: Foto -->
            <div class="form-section">
                <h3 class="section-title">🖼️ Foto del Estudiante</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto Actual</label>
                            @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto de {{ $student->first_name }} {{ $student->last_name }}" class="photo-preview current-photo">
                        <div class="form-text mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo">
                                <label class="form-check-label" for="remove_photo">Eliminar Foto Actual</label>
                            </div>
                        </div>
                        @else
                        <div class="form-text">No hay foto actual</div>
                        @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo" class="form-label fw-bold">Nueva foto:</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif" value="{{ old('photo') }}" onchange="previewImage(this)">
                        <div class="form-text">
                            📷 Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                        </div>
                        <!-- Preview de la nueva imagen-->
                        <div class="mt-2">
                            <img id="photoPreview" src="" alt="Preview de la foto" class="photo-preview d-none">
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Seccion: Datos Personales -->
    <div class="form-section">
        <h3 class="section-title">👤 Datos Personales</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="student_code" class="form-label">Codigo del Estudiante</label>
                    <input type="text" class="form-control" id="student_code" name="student_code" value="{{ old('student_code', $student->student_code) }}" required>
                    <div class="form-text">Codigo Unico del Estudiante</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="enrollment_year" class="form-label">Año de Matrícula</label>
                    <input type="number" class="form-control" id="enrollment_year" name="enrollment_year" value="{{ old('enrollment_year', $student->enrollment_year) }}" required>
                    <div class="form-text">Año de Matrícula del Estudiante</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
                    <div class="form-text">Nombre del Estudiante</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
                    <div class="form-text">Apellido del Estudiante</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Seccion: Informacion de Contacto -->
    <div class="form-section">
        <h3 class="section-title">Informacion de Contacto</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phone" class="form-label">Telefono</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $student->phone) }}" required>
                    <div class="form-text">Telefono del Estudiante</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $student->email) }}" required>
                    <div class="form-text">Email del Estudiante</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $student->address) }}" required>
                    <div class="form-text">Direccion del Estudiante</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Seccion: Informacion Academica-->
    <div class="form-section">
        <h3 class="section-title">Informacion Academica</h3>
        <div class="mb-3">
            <label for="career" class="form-label">Carrera</label>
            <select class="form-select" id="career" name="career" value="{{ old('career', $student->career) }}" required>
                <option value="">Seleccione una carrera</option>
                <option value="Ingenieria en Sistemas">Ingenieria en Sistemas</option>
                <option value="Ingenieria en Software">Ingenieria en Software</option>
                <option value="Ingenieria en Redes">Ingenieria en Redes</option>
                <option value="Ingenieria en Computacion">Ingenieria en Computacion</option>
            </select>
        </div>
    </div>

    <!-- Botones de accion -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success btn-lg">Actualizar Estudiante</button>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-info">Ver Detalles</a>
                <a href="{{ route('students.index') }}" class="btn btn-outline-danger btn-lg">Cancelar</a>
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