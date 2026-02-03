// Interfaces para las respuestas de la API

export interface User {
  id: number;
  email: string;
  rol: 'admin' | 'docente' | 'alumno';
  estatus: string;
  perfil_alumno: StudentProfile | null;
  perfil_docente: TeacherProfile | null;
}

export interface StudentProfile {
  matricula: string;
  curp: string;
  first_name: string;
  last_name: string;
  birth_date: string;
  career_id: number;
}

export interface TeacherProfile {
  first_name: string;
  last_name: string;
}

export interface LoginResponse {
  message: string;
  token: string;
  user: User;
}

export interface Course {
  id_clase: number;
  nombre_materia: string;
  profesor: string;
  salon: string;
  horario: {
    dia: string;
    inicio: string;
    fin: string;
  };
  cupo: {
    maximo?: number;
    actual: number;
    disponible: number;
  };
  periodo?: string;
  grupo?: string;
}

export interface Kardex {
  id: number;
  calificaciones: {
    p1: number | null;
    p2: number | null;
    final: number | null;
  };
  materia: {
    nombre: string;
    creditos: number;
  };
  periodo: string;
}

export interface Payment {
  id: number;
  amount: number;
  concept: string;
  due_date: string;
  status: 'Pagado' | 'Pendiente';
  paid_at: string | null;
}

export interface CreateCourseRequest {
  period_id: number;
  subject_id: number;
  teacher_id: number;
  classroom_id: number;
  group_name: string;
  day_of_week: string;
  start_time: string;
  end_time: string;
  max_quota: number;
}

export interface RegisterStudentRequest {
  email: string;
  password: string;
  matricula: string;
  curp: string;
  first_name: string;
  last_name: string;
  birth_date: string;
  career_id: number;
}

export interface SubmitGradeRequest {
  enrollment_id: number;
  grade: number;
  type: 'p1' | 'p2' | 'final';
}
