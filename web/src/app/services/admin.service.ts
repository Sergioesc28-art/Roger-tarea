import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { RegisterStudentRequest, CreateCourseRequest, Course } from '../models/api.models';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  registerStudent(data: RegisterStudentRequest): Observable<{ message: string; id: number }> {
    return this.http.post<{ message: string; id: number }>(
      `${this.apiUrl}/admin/register/student`,
      data
    );
  }

  createCourse(data: CreateCourseRequest): Observable<{ message: string; data: Course }> {
    return this.http.post<{ message: string; data: Course }>(
      `${this.apiUrl}/admin/courses`,
      data
    );
  }

  // Métodos adicionales que podrías necesitar
  getPeriods(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin/periods`);
  }

  getSubjects(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin/subjects`);
  }

  getTeachers(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin/teachers`);
  }

  getClassrooms(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin/classrooms`);
  }

  getCareers(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/admin/careers`);
  }
}
