import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

export interface Student {
  id?: number;
  matricula: string;
  first_name: string;
  last_name: string;
  email: string;
  career_id: number;
  curp: string;
  birth_date: string;
}

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private http = inject(HttpClient);
  private apiUrl = environment.apiUrl + '/admin'; // /api/admin

  // === ALUMNOS ===
  
  // Registrar Alumno
  createStudent(data: any) {
    return this.http.post(`${this.apiUrl}/register/student`, data);
  }

  // Obtener lista (Aún no tienes este endpoint en Laravel, pero lo necesitaremos pronto)
  // Por ahora lo simularemos o usaremos el endpoint público de clases si es necesario
  getStudents() {
    // TIP: Necesitarás crear un endpoint GET /admin/students en Laravel más adelante
    return this.http.get<Student[]>(`${this.apiUrl}/students`); 
  }

  // === DOCENTES ===
  createTeacher(data: any) {
    return this.http.post(`${this.apiUrl}/register/teacher`, data);
  }

  // === CURSOS ===
  createCourse(data: any) {
    return this.http.post(`${this.apiUrl}/courses`, data);
  }
}