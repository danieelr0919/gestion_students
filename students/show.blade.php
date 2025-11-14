<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Estudiante - {{ $student->name }} {{ $student->last_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
        }

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #495057;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #007bff;
        }

        .info-label {
            font-weight: 500;
            color: #6c757d;
        }

        .info-value {
            color: #212529;
        }

        .photo-container {
            text-align: center;
            padding: 1rem;
        }

        .student-photo{
            width: 100%;
            max-width: 300px;
            height: 300px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('students.index') }}">Estudiantes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header con gradiente -->
    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="lead mb-0">Código: {{ $student->student_code }} | Carrera: {{ $student->career }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('students.index') }}" class="btn btn-light btn-sm me-2">
                        ← Volver a la Lista
                    </a>
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                        ✏️ Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Columna Izquierda - Foto -->
            <div class="col-md-4">
                <div class="card profile-card h-100">
                    <div class="photo-container">
                        @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                            alt="Foto de {{ $student->first_name }} {{ $student->last_name }}"
                            class="student-photo rounded-circle">
                        @else
                        <div class="student-photo rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto">
                            <div class="text-center text-muted">
                                <i class="fas fa-user" style="font-size: 4rem;"></i>
                                <p class="mt-2">Sin foto</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h4>
                        <p class="text-muted">{{ $student->career }}</p>

                        <!-- Botones de acción -->
                        <div class="btn-group-vertical w-100" role="group">
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning mb-2">
                                ✏️ Editar Información
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger w-100"
                                    onclick="return confirm('¿Estás seguro de eliminar permanentemente a {{ $student->first_name }} {{ $student->last_name }}?')">
                                    🗑️ Eliminar Estudiante
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha - Información -->
            <div class="col-md-8">
                <!-- Sección: Datos Personales -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">👤 Datos Personales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Código de Estudiante:</span>
                                <div class="info-value">{{ $student->student_code }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Nombre Completo:</span>
                                <div class="info-value">{{ $student->first_name }} {{ $student->last_name }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Año de Matrícula:</span>
                                <div class="info-value">
                                    <span class="badge bg-info">{{ $student->enrollment_year }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Carrera:</span>
                                <div class="info-value">
                                    <span class="badge bg-success">{{ $student->career }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Información de Contacto -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📞 Información de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Email:</span>
                                <div class="info-value">
                                    <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                        📧 {{ $student->email }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Teléfono:</span>
                                <div class="info-value">
                                    @if($student->phone)
                                    📞 {{ $student->phone }}
                                    @else
                                    <span class="text-muted">No registrado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="info-label">Dirección:</span>
                                <div class="info-value">
                                    @if($student->address)
                                    🏠 {{ $student->address }}
                                    @else
                                    <span class="text-muted">No registrada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Información Académica -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">🎓 Información Académica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Carrera:</span>
                                <div class="info-value">{{ $student->career }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Año de Ingreso:</span>
                                <div class="info-value">{{ $student->enrollment_year }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Años de Estudio:</span>
                                <div class="info-value">
                                    @php
                                    $years_studied = date('Y') - $student->enrollment_year;
                                    @endphp
                                    <span class="badge bg-primary">{{ $years_studied }} año(s)</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Estado:</span>
                                <div class="info-value">
                                    <span class="badge bg-success">Activo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección: Información de Registro -->
                <div class="card profile-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">📊 Información de Registro</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="info-label">ID del Estudiante:</span>
                                <div class="info-value">#{{ $student->id }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Código Único:</span>
                                <div class="info-value">{{ $student->student_code }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Fecha de Creación:</span>
                                <div class="info-value">
                                    @if($student->created_at)
                                    {{ $student->created_at->format('d/m/Y') }}
                                    @else
                                    <span class="text-muted">No disponible</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="info-label">Última Actualización:</span>
                                <div class="info-value">
                                    @if($student->updated_at)
                                    {{ $student->updated_at->format('d/m/Y') }}
                                    @else
                                    <span class="text-muted">No disponible</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones inferiores -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="btn-group" role="group">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        ← Volver a la Lista
                    </a>
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">
                        ✏️ Editar Estudiante
                    </a>
                    <a href="{{ route('students.create') }}" class="btn btn-success">
                        ➕ Agregar Nuevo Estudiante
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">Sistema de Gestión de Estudiantes &copy; {{ date('Y') }}</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>