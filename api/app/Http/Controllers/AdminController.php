<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Career;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // === REGISTRO DE ALUMNOS (Transaccional) ===
    public function registerStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'matricula' => 'required|unique:students,enrollment_number',
            'curp' => 'required|unique:students,curp',
            'first_name' => 'required',
            'last_name' => 'required',
            'career_id' => 'required|exists:careers,id',
            'birth_date' => 'required|date'
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                // 1. Crear Usuario
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => 3, // ID 3 = Alumno (según tu DB seed)
                    'active' => true,
                ]);

                // 2. Crear Perfil
                $student = Student::create([
                    'user_id' => $user->id,
                    'career_id' => $request->career_id,
                    'enrollment_number' => $request->matricula,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'curp' => $request->curp,
                    'birth_date' => $request->birth_date,
                    'current_quarter' => 1
                ]);

                return $student;
            });

            return response()->json(['message' => 'Alumno registrado con éxito', 'id' => $result->id], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en el registro: ' . $e->getMessage()], 500);
        }
    }

    // === REGISTRO DE DOCENTES (Transaccional) ===
    public function registerTeacher(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'rfc' => 'required|unique:teachers,rfc'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => 2, // ID 2 = Docente
                    'active' => true,
                ]);

                Teacher::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'rfc' => $request->rfc,
                    'phone_number' => $request->phone_number ?? null,
                ]);
            });

            return response()->json(['message' => 'Docente registrado con éxito'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function indexStudents()
    {
        // Traemos 15 alumnos por página, ordenados por los más nuevos
        $students = Student::with(['user', 'career'])
            ->orderBy('created_at', 'desc')
            ->paginate(15); 

        // Formateamos la data interna sin perder la paginación (links, total, etc.)
        $students->through(function ($student) {
            return [
                'id' => $student->id,
                'matricula' => $student->enrollment_number,
                'full_name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->user->email ?? 'Sin usuario',
                'career' => $student->career->name ?? 'N/A',
                'semester' => $student->current_quarter,
                'status' => $student->user->active ? 'Activo' : 'Baja',
                'avatar_initial' => substr($student->first_name, 0, 1) // Para el circulito de color
            ];
        });

        return response()->json($students);
    }

    // ==========================================
    // 2. GESTIÓN DE DOCENTES (Paginado)
    // ==========================================
    public function indexTeachers()
    {
        // Asumiendo que tienes un modelo Teacher
        $teachers = Teacher::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $teachers->through(function ($teacher) {
            return [
                'id' => $teacher->id,
                'code' => $teacher->employee_number, // Número de empleado
                'full_name' => $teacher->first_name . ' ' . $teacher->last_name,
                'email' => $teacher->user->email ?? 'Sin usuario',
                'phone' => $teacher->phone_number,
                'status' => $teacher->user->active ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json($teachers);
    }

    // ==========================================
    // 3. GESTIÓN DE CLASES/CURSOS (Paginado)
    // ==========================================
    public function indexCourses()
    {
        // Las clases suelen tener relación con Materia y Docente
        $courses = Course::with(['subject', 'teacher'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $courses->through(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->subject->name, // Nombre de la materia
                'teacher' => $course->teacher ? ($course->teacher->first_name . ' ' . $course->teacher->last_name) : 'Sin asignar',
                'schedule' => $course->schedule_info, // Ej: "Lun-Mie 8:00"
                'students_count' => $course->students_count ?? 0, // Si usas withCount en la query
                'status' => $course->is_active ? 'En Curso' : 'Finalizado'
            ];
        });

        return response()->json($courses);
    }
    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'career_id' => 'required|exists:careers,id',
        ]);

        try {
            DB::transaction(function () use ($request, $student, $user) {
                // Actualizar User
                $user->update(['email' => $request->email]);
                
                // Actualizar Perfil
                $student->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'career_id' => $request->career_id,
                    // Agrega más campos si es necesario
                ]);
            });

            return response()->json(['message' => 'Alumno actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar'], 500);
        }
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Invertimos el estado (Si es true pasa a false, y viceversa)
        $user->active = !$user->active;
        $user->save();

        $status = $user->active ? 'activado' : 'desactivado';
        return response()->json(['message' => "Usuario $status correctamente"]);
    }

    // === GESTIÓN DE CARRERAS (CRUD Básico) ===
    public function indexCareers()
    {
        // Paginación de carreras (pocas, pero por si acaso crece)
        $careers = Career::orderBy('name', 'asc')->paginate(15);
        return response()->json($careers);
    }

    public function storeCareer(Request $request)
    {
        $request->validate(['name' => 'required|unique:careers,name', 'rvoe' => 'required']);
        $career = Career::create($request->all());
        return response()->json(['message' => 'Carrera creada', 'data' => $career], 201);
    }

    // === GESTIÓN DE MATERIAS (CRUD Básico) ===
    public function indexSubjects()
    {
        $subjects = Subject::orderBy('name', 'asc')->paginate(20);
        return response()->json($subjects);
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'credits' => 'required|numeric',
            'semester' => 'required|numeric' // Semestre sugerido
        ]);
        
        $subject = Subject::create($request->all());
        return response()->json(['message' => 'Materia creada', 'data' => $subject], 201);
    }
}