<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('students.index') }}">Estudiantes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-primary">Lista de Estudiantes</h1>
        <!-- Boton para crear nuevo estudiante -->
        <a href="{{ route('students.create') }}" class="btn btn-primary">Agregar Nuevo Estudiante</a>

        <!-- Mostrar mensajes de exito -->
         @if (session('success'))
         <div class="alert alert-success" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
            </button>
        </div>
         @endif
    
    </div>

    <!-- Creamos la tabla de estudiantes -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Carrera</th>
                    <th>Año de Ingreso</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                        <td>{{ $student->student_code }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone ?? 'N/A' }}</td>
                        <td>{{ $student->address ?? 'N/A' }}</td>
                        <td>{{ $student->career }}</td>
                        <td>{{ $student->enrollment_year }}</td>
                        <td>
                            @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto de {{ $student->first_name }} {{ $student->last_name }}" width="50" height="50">
                            @else
                            <span class="text-muted">Sin foto</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <!-- Boton para ver detalles del estudiante -->
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-info">Ver detalles</a>
                            
                                <!-- Boton para editar estudiante -->
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">Editar</a>

                                <!-- Boton para eliminar estudiante -->
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estas seguro de eliminar a {{ $student->first_name }} {{ $student->last_name }}?')">Eliminar</button>
                                </form>
                            </div>
                        </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-4">
                        <div class="alert alert-warning">
                            <h5>No hay estudiantes registrados</h5>
                            <p class="mb-0">Por favor, registra un estudiante para verlo aqui</p>
                            <a href="{{ route('students.create') }}" class="btn btn-primary">Crear Estudiante</a>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Contador de estudiantes -->
    <div class="text-center mt-4">
        <strong>Total de estudiantes:</strong> {{ $students->count() }}
    </div>


    <!--Paginacion -->
    @if(method_exists($students, 'links'))
    <div class="d-flex justify-content-center">
        {{ $students->links() }}
    </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('home') }}" class="btn btn-primary">Regresar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>