import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Course, SubmitGradeRequest } from '../models/api.models';

@Injectable({
  providedIn: 'root'
})
export class TeacherService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getSchedule(): Observable<{ data: Course[] }> {
    return this.http.get<{ data: Course[] }>(
      `${this.apiUrl}/teacher/schedule`
    );
  }

  submitGrade(data: SubmitGradeRequest): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(
      `${this.apiUrl}/teacher/grades`,
      data
    );
  }

  // Método adicional para obtener estudiantes de una clase
  getClassStudents(courseId: number): Observable<any[]> {
    return this.http.get<any[]>(
      `${this.apiUrl}/teacher/courses/${courseId}/students`
    );
  }
}
