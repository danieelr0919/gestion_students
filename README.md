🎓 GUÍA MAESTRA: SISTEMA DE GESTIÓN DE ESTUDIANTES EN LARAVEL
📚 TABLA DE CONTENIDOS
Arquitectura del Sistema

Modelo Student

Controlador StudentController

Vistas Blade

Base de Datos

Rutas

Patrones Reutilizables

1. ARQUITECTURA DEL SISTEMA
🔧 PATRÓN MVC (Modelo-Vista-Controlador)
text
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│     VISTA       │    │   CONTROLADOR    │    │     MODELO      │
│  (Interfaz)     │◄──►│   (Lógica)       │◄──►│   (Datos)       │
│                 │    │                  │    │                 │
│ • Blade Templates│   │ • StudentController│   │ • Student.php   │
│ • Bootstrap 5   │    │ • 7 métodos CRUD  │    │ • $fillable     │
│ • HTML/CSS      │    │ • Validación      │    │ • Accessors     │
└─────────────────┘    └──────────────────┘    └─────────────────┘
         │                        │                        │
         │                        │                        │
         └────────────────────────┼────────────────────────┘
                                  │
                         ┌─────────────────┐
                         │   BASE DE DATOS │
                         │                 │
                         │ • Tabla students│
                         │ • MySQL         │
                         └─────────────────┘
🔄 FLUJO DE DATOS COMPLETO:
CREAR ESTUDIANTE:

text
Usuario → Formulario CREATE → Ruta POST /students 
→ StudentController@store → Validación → Guardar foto 
→ Student::create() → Redirigir a INDEX con mensaje
VER ESTUDIANTE:

text
Usuario → Lista INDEX → Clic "Ver" → Ruta GET /students/{id}
→ StudentController@show → Student::findOrFail() 
→ Vista SHOW con datos
2. MODELO STUDENT
📄 CÓDIGO COMPLETO: app/Models/Student.php
php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'career',
        'enrollment_year',
        'photo',
    ];

    // Accessor para nombre completo
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Accessor para URL de la foto
    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-avatar.png');
    }
}
🔍 ANÁLISIS DETALLADO:
A. protected $fillable
php
protected $fillable = [
    'student_code', 'first_name', 'last_name', 'email', 
    'phone', 'address', 'career', 'enrollment_year', 'photo'
];
¿QUÉ HACE?

Seguridad: Define qué campos pueden ser llenados masivamente

Protección: Evita que usuarios malintencionados envíen campos no autorizados

¿POR QUÉ ES NECESARIO?

php
// PELIGRO: Sin $fillable
$data = $request->all(); // Usuario podría enviar 'is_admin' => true
Student::create($data);  // ¡Se guardaría el campo is_admin!

// SEGURO: Con $fillable  
$data = $request->all(); // Usuario envía 'is_admin' => true
Student::create($data);  // 'is_admin' se ignora, solo se guardan campos en $fillable
🔁 PATRÓN REUTILIZABLE:

php
// Para cualquier modelo, siempre incluir:
protected $fillable = [
    // Lista TODOS los campos que vendrán de formularios
];
B. ACCESSORS (Atributos Virtuales)
getFullNameAttribute()

php
public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->last_name}";
}
¿CÓMO SE USA?

php
// En controladores:
$student->full_name; // "Juan Pérez"

// En vistas Blade:
{{ $student->full_name }} // Muestra "Juan Pérez"
¿QUÉ VENTAJAS TIENE?

✅ Lógica centralizada: Si cambia el formato, solo cambias aquí

✅ Código más limpio: No concatenas en cada vista

✅ Reutilizable: Se usa igual en controladores y vistas

getPhotoUrlAttribute()

php
public function getPhotoUrlAttribute()
{
    return $this->photo
        ? asset('storage/' . $this->photo)
        : asset('images/default-avatar.png');
}
¿QUÉ HACE?

Si hay foto: Genera URL completa → http://dominio.com/storage/students/foto.jpg

Si no hay foto: Retorna imagen por defecto

🔁 PATRÓN REUTILIZABLE PARA ACCESSORS:

php
// Convención: get[NombreAtributo]Attribute()
public function get[Nombre]Attribute()
{
    // Lógica para calcular/combinar campos
    return $resultado;
}
// Uso: $objeto->nombre (sin paréntesis)
3. CONTROLADOR STUDENTCONTROLLER
📄 CÓDIGO COMPLETO: app/Http/Controllers/StudentController.php
php
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // MÉTODO INDEX - Listar estudiantes
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    // MÉTODO CREATE - Mostrar formulario
    public function create()
    {
        return view('students.create');
    }

    // MÉTODO STORE - Guardar nuevo estudiante
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_code' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'career' => 'required',
            'enrollment_year' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = $path;
        }

        Student::create($validated);
        return redirect()->route('students.index')
            ->with('success', 'Estudiante creado exitosamente');
    }

    // MÉTODO SHOW - Mostrar detalles
    public function show(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    // MÉTODO EDIT - Mostrar formulario edición
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // MÉTODO UPDATE - Actualizar estudiante
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'student_code' => 'required|unique:students,student_code,' . $student->id,
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'career' => 'required|string|max:100',
            'enrollment_year' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = $path;
        }

        $student->update($validated);
        return redirect()->route('students.index')
            ->with('success', 'Estudiante actualizado exitosamente');
    }

    // MÉTODO DESTROY - Eliminar estudiante
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Estudiante eliminado exitosamente');
    }
}
🔍 ANÁLISIS DETALLADO DE CADA MÉTODO:
A. MÉTODO INDEX - Listar Estudiantes
php
public function index()
{
    $students = Student::all();
    return view('students.index', compact('students'));
}
¿QUÉ HACE?

Student::all() → Ejecuta: SELECT * FROM students

compact('students') → Crea array: ['students' => $students]

Pasa datos a la vista students.index

🔁 PATRÓN REUTILIZABLE:

php
public function index()
{
    $registros = Modelo::all(); // o Modelo::paginate(10)
    return view('vista.index', compact('registros'));
}
B. MÉTODO CREATE - Formulario Vacío
php
public function create()
{
    return view('students.create');
}
¿POR QUÉ TAN SIMPLE?

Solo necesita mostrar formulario vacío

No requiere datos de la base de datos

C. MÉTODO STORE - Guardar Nuevo Registro
PARTE 1: VALIDACIÓN

php
$validated = $request->validate([
    'student_code' => 'required',
    'first_name' => 'required',
    'last_name' => 'required', 
    'email' => 'required|email',
    'phone' => 'required',
    'address' => 'required',
    'career' => 'required',
    'enrollment_year' => 'required',
    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
]);
REGLAS DE VALIDACIÓN USADAS:

required → Campo obligatorio

email → Formato de email válido

image → Debe ser archivo de imagen

mimes:jpeg,png,jpg,gif → Formatos permitidos

max:2048 → Tamaño máximo 2MB

¿QUÉ PASA SI LA VALIDACIÓN FALLA?

Laravel redirige automáticamente al formulario anterior

Mantiene los datos enviados (disponibles via old())

Muestra errores en la variable $errors

PARTE 2: MANEJO DE ARCHIVOS

php
if ($request->hasFile('photo')) {
    $path = $request->file('photo')->store('students', 'public');
    $validated['photo'] = $path;
}
hasFile('photo') vs $request->photo

hasFile() → Verifica específicamente si es un ARCHIVO

$request->photo → Podría ser cualquier tipo de dato

store('students', 'public')

'students' → Carpeta dentro de storage/app/public/

'public' → Disco de almacenamiento configurado

Retorna: Ruta relativa → 'students/nombre_archivo.jpg'

PARTE 3: CREACIÓN EN BD

php
Student::create($validated);
¿QUÉ HACE INTERNAMENTE?

sql
INSERT INTO students (student_code, first_name, last_name, email, phone, address, career, enrollment_year, photo, created_at, updated_at) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
PARTE 4: REDIRECCIÓN CON MENSAJE

php
return redirect()->route('students.index')
    ->with('success', 'Estudiante creado exitosamente');
with('success', 'mensaje')

Guarda mensaje en la sesión flash

Existe solo por una petición (se autodestruye)

Se muestra en la siguiente vista

D. MÉTODO SHOW - Mostrar Detalles
php
public function show(string $id)
{
    $student = Student::findOrFail($id);
    return view('students.show', compact('student'));
}
findOrFail($id) vs find($id)

php
// find() - Si no encuentra, retorna null
$student = Student::find(9999); // Retorna: null

// findOrFail() - Si no encuentra, lanza Exception
$student = Student::findOrFail(9999); // Lanza: ModelNotFoundException (Error 404)
¿POR QUÉ USAR findOrFail?

✅ Mejor seguridad → Evita errores de variable null

✅ Mejor UX → Laravel muestra página 404 automáticamente

✅ Menos código → No necesitas verificar si existe

E. MÉTODO EDIT - Formulario de Edición
php
public function edit(string $id)
{
    $student = Student::findOrFail($id);
    return view('students.edit', compact('student'));
}
¿QUÉ PASA EN LA VISTA?

blade
<input value="{{ old('first_name', $student->first_name) }}">
<!-- 
- Si hay error: usa old('first_name') (valor que usuario acababa de escribir)
- Si no hay error: usa $student->first_name (valor actual de BD)
-->
F. MÉTODO UPDATE - Actualizar Registro
VALIDACIÓN CON EXCEPCIÓN ÚNICA:

php
'student_code' => 'required|unique:students,student_code,' . $student->id,
'email' => 'required|email|unique:students,email,' . $student->id,
¿QUÉ SIGNIFICA ESTO?

php
unique:students,          // Tabla donde verificar
student_code,            // Columna a verificar
'.$student->id           // ID a excluir de la verificación
EJEMPLO PRÁCTICO:

Estudiante con ID=5 tiene código "A001"

Usuario edita pero NO cambia el código

Validación: "¿Existe otro estudiante (diferente al ID=5) con código A001?"

Si NO existe → ✅ Pasa validación

Si existe → ❌ Error de duplicado

MANEJO AVANZADO DE FOTOS:

php
if ($request->hasFile('photo')) {
    // 1. Eliminar foto anterior SI existe
    if ($student->photo) {
        Storage::disk('public')->delete($student->photo);
    }
    
    // 2. Subir nueva foto
    $path = $request->file('photo')->store('students', 'public');
    $validated['photo'] = $path;
}
¿POR QUÉ ELIMINAR LA FOTO ANTERIOR?

✅ Evita basura en el servidor

✅ Ahorra espacio de almacenamiento

✅ Mantiene limpio el sistema de archivos

ACTUALIZACIÓN EN BD:

php
$student->update($validated);
¿QUÉ HACE INTERNAMENTE?

sql
UPDATE students 
SET student_code=?, first_name=?, last_name=?, email=?, phone=?, address=?, career=?, enrollment_year=?, photo=?, updated_at=NOW() 
WHERE id=?
G. MÉTODO DESTROY - Eliminar Registro
php
public function destroy(string $id)
{
    $student = Student::findOrFail($id);
    
    // 1. Eliminar foto del almacenamiento
    if ($student->photo) {
        Storage::disk('public')->delete($student->photo);
    }
    
    // 2. Eliminar registro de la BD
    $student->delete();
    
    return redirect()->route('students.index')
        ->with('success', 'Estudiante eliminado exitosamente');
}
¿POR QUÉ ELIMINAR LA FOTO PRIMERO?

php
// ORDEN CORRECTO:
1. Eliminar archivos del storage
2. Eliminar registro de la BD

// ORDEN INCORRECTO (PROBLEMA):
1. Eliminar registro de la BD ← $student->photo ya no existe!
2. Eliminar archivos del storage
Storage::disk('public')->delete($student->photo)

disk('public') → Apunta a storage/app/public/

delete($ruta) → Elimina el archivo físico

ELIMINACIÓN EN BD:

php
$student->delete();
¿QUÉ HACE INTERNAMENTE?

sql
DELETE FROM students WHERE id=?
4. VISTAS BLADE
🏗️ ESTRUCTURA COMÚN DE TODAS LAS VISTAS:
html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título</title>
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- NAVBAR UNIFICADO -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Gestión</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                <a class="nav-link" href="{{ route('students.index') }}">Estudiantes</a>
                <a class="nav-link" href="{{ route('products.index') }}">Productos</a>
                <a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO ESPECÍFICO -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
A. VISTA WELCOME - Página de Inicio
CARACTERÍSTICAS PRINCIPALES:

Cards de navegación para cada módulo

Diseño responsive con Bootstrap

Accesos rápidos a todas las funcionalidades

CÓDIGO CLAVE EXPLICADO:

blade
<!-- Cards con enlaces a módulos -->
<div class="card h-100 shadow-sm">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">Gestión de Estudiantes</h5>
        <p class="card-text text-muted">Administra estudiantes...</p>
        <div class="mt-auto d-flex gap-2">
            <a href="{{ route('students.index') }}" class="btn btn-primary">Ir a Estudiantes</a>
            <a href="{{ route('students.create') }}" class="btn btn-outline-primary">Nuevo Estudiante</a>
        </div>
    </div>
</div>
B. VISTA INDEX - Lista de Estudiantes
CARACTERÍSTICAS PRINCIPALES:

Tabla responsive con todos los campos

Botones de acciones (Ver, Editar, Eliminar)

Manejo de estados vacíos con @forelse

Contador de registros

Paginación lista

CÓDIGO CLAVE EXPLICADO:

blade
<!-- @forelse vs @foreach -->
@forelse ($students as $student)
    <!-- Si hay datos, muestra cada estudiante -->
@empty
    <!-- Si NO hay datos, muestra mensaje -->
    <tr>
        <td colspan="11" class="text-center">
            <div class="alert alert-warning">
                <h5>No hay estudiantes registrados</h5>
                <a href="{{ route('students.create') }}" class="btn btn-primary">Crear Estudiante</a>
            </div>
        </td>
    </tr>
@endforelse

<!-- Operador de null coalescente -->
<td>{{ $student->phone ?? 'N/A' }}</td>
<!-- Si $student->phone es null, muestra 'N/A' -->

<!-- Mostrar imágenes -->
@if($student->photo)
    <img src="{{ asset('storage/' . $student->photo) }}" width="50" height="50">
@else
    <span class="text-muted">Sin foto</span>
@endif

<!-- Formulario DELETE -->
<form action="{{ route('students.destroy', $student->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" 
            onclick="return confirm('¿Estás seguro?')">
        Eliminar
    </button>
</form>
<!-- @method('DELETE') simula petición DELETE -->
C. VISTA CREATE - Formulario de Creación
CARACTERÍSTICAS PRINCIPALES:

Validación HTML5 (required, type, pattern)

Estructura de 2 columnas responsive

Manejo de errores con $errors

Mantenimiento de datos con old()

Subida de archivos con enctype="multipart/form-data"

CÓDIGO CLAVE EXPLICADO:

blade
<!-- Formulario con subida de archivos -->
<form action="{{ route('students.store') }}" method="POST" 
      enctype="multipart/form-data">
    @csrf
    <!-- Campos del formulario -->
</form>

<!-- Manejo de errores -->
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Mantener valores después de error -->
<input value="{{ old('student_code') }}" required>
<!-- old() recupera valor anterior si hubo error -->

<!-- Campo de archivo -->
<input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif">
<!-- accept limita tipos de archivo -->
D. VISTA EDIT - Formulario de Edición
DIFERENCIAS CON CREATE:

@method('PUT') para actualizaciones

Valores actuales + valores anteriores

Manejo de foto actual + opción eliminar

CÓDIGO CLAVE EXPLICADO:

blade
<!-- Método PUT para actualizar -->
<form action="{{ route('students.update', $student->id) }}" method="POST">
    @csrf
    @method('PUT')
</form>

<!-- Valores inteligentes -->
<input value="{{ old('first_name', $student->first_name) }}">
<!-- old() tiene prioridad, sino valor actual -->

<!-- Manejo de foto actual -->
@if($student->photo)
    <img src="{{ asset('storage/' . $student->photo) }}">
    <input type="checkbox" name="remove_photo"> Eliminar foto
@endif
E. VISTA SHOW - Detalles del Estudiante
CARACTERÍSTICAS PRINCIPALES:

Diseño de 2 columnas (foto + información)

Secciones organizadas (Datos, Contacto, Académicos)

Botones de acción contextuales

Información calculada (años de estudio)

CÓDIGO CLAVE EXPLICADO:

blade
<!-- Cálculos dinámicos -->
@php
    $years_studied = date('Y') - $student->enrollment_year;
@endphp
<span class="badge bg-primary">{{ $years_studied }} año(s)</span>

<!-- Uso de accessors -->
<h2>{{ $student->full_name }}</h2>
<!-- Automáticamente usa getFullNameAttribute() -->
5. BASE DE DATOS
📊 MIGRACIÓN: database/migrations/xxx_create_students_table.php
php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('career');
            $table->integer('enrollment_year');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
🔍 EXPLICACIÓN DE TIPOS DE DATOS:
Tipo	Descripción	Ejemplo
id()	Primary key autoincremental	1, 2, 3...
string('nombre')	VARCHAR(255)	"Juan Pérez"
string()->unique()	VARCHAR único	"A001" (no se repite)
string()->nullable()	VARCHAR opcional	NULL si no se llena
integer('año')	Número entero	2024
timestamps()	created_at, updated_at	Automáticos
🚀 EJECUCIÓN DE MIGRACIONES:
bash
# Crear las tablas en la base de datos
php artisan migrate

# Ver estado de migraciones
php artisan migrate:status

# Revertir última migración
php artisan migrate:rollback
6. RUTAS
🌐 ARCHIVO: routes/web.php
php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('students', StudentController::class);
🔍 EXPLICACIÓN DE Route::resource():
RUTAS AUTOMÁTICAMENTE GENERADAS:

Método	URL	Acción	Nombre
GET	/students	index	students.index
GET	/students/create	create	students.create
POST	/students	store	students.store
GET	/students/{id}	show	students.show
GET	/students/{id}/edit	edit	students.edit
PUT/PATCH	/students/{id}	update	students.update
DELETE	/students/{id}	destroy	students.destroy
📝 USO EN VISTAS BLADE:
blade
{{ route('students.index') }}    <!-- /students -->
{{ route('students.create') }}   <!-- /students/create -->
{{ route('students.show', 1) }}  <!-- /students/1 -->
{{ route('students.edit', 1) }}  <!-- /students/1/edit -->
🔁 PATRÓN REUTILIZABLE:
php
// Para cualquier CRUD:
Route::resource('entidad', EntidadController::class);
// Genera automáticamente las 7 rutas RESTful
7. PATRONES REUTILIZABLES
🎯 PLANTILLA PARA CUALQUIER CRUD:
A. ESTRUCTURA BÁSICA:
text
1. 📝 Crear migración
2. 🎯 Crear modelo con $fillable
3. 🎮 Crear controlador resource
4. 🌐 Definir rutas
5. 🎨 Crear vistas base
B. PLANTILLA DE CONTROLADOR:
php
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TuModelo;

class TuController extends Controller
{
    public function index()
    {
        $registros = TuModelo::all();
        return view('vista.index', compact('registros'));
    }

    public function create()
    {
        return view('vista.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([/* reglas */]);
        TuModelo::create($validated);
        return redirect()->route('vista.index')->with('success', 'Creado');
    }

    public function show($id)
    {
        $registro = TuModelo::findOrFail($id);
        return view('vista.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = TuModelo::findOrFail($id);
        return view('vista.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = TuModelo::findOrFail($id);
        $validated = $request->validate([/* reglas */]);
        $registro->update($validated);
        return redirect()->route('vista.index')->with('success', 'Actualizado');
    }

    public function destroy($id)
    {
        $registro = TuModelo::findOrFail($id);
        $registro->delete();
        return redirect()->route('vista.index')->with('success', 'Eliminado');
    }
}
C. COMANDOS RÁPIDOS:
bash
# Crear modelo + migración + controlador
php artisan make:model Modelo -mcr

# Solo controlador resource
php artisan make:controller ModeloController --resource

# Solo migración
php artisan make:migration create_tabla_table
D. VALIDACIONES COMUNES:
php
'campo' => 'required',
'email' => 'required|email',
'unico' => 'required|unique:tabla,campo,'.$id,
'numero' => 'required|integer|min:0',
'archivo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
'texto_largo' => 'required|string|max:255',
E. MANEJO DE ARCHIVOS:
php
// Subir archivo
if ($request->hasFile('archivo')) {
    $path = $request->file('archivo')->store('carpeta', 'public');
    $validated['archivo'] = $path;
}

// Eliminar archivo anterior
if ($registro->archivo) {
    Storage::disk('public')->delete($registro->archivo);
}
🎓 CONCLUSIÓN
✅ LO QUE APRENDISTE:
Arquitectura MVC completa en Laravel

Modelos Eloquent con seguridad ($fillable) y accessors

Controladores Resource con los 7 métodos RESTful

Vistas Blade profesionales con Bootstrap

Validación de datos robusta

Manejo de archivos seguro

Rutas RESTful automáticas

Mensajes flash para feedback al usuario
