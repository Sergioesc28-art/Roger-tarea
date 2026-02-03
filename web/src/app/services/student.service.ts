import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Course, Kardex, Payment } from '../models/api.models';

@Injectable({
  providedIn: 'root'
})
export class StudentService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getCourses(periodId?: number): Observable<{ data: Course[] }> {
    const url = periodId 
      ? `${this.apiUrl}/courses?period_id=${periodId}`
      : `${this.apiUrl}/courses`;
    return this.http.get<{ data: Course[] }>(url);
  }

  enrollCourse(courseId: number): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(
      `${this.apiUrl}/student/enroll`,
      { course_id: courseId }
    );
  }

  getKardex(): Observable<Kardex[]> {
    return this.http.get<Kardex[]>(
      `${this.apiUrl}/student/kardex`
    );
  }

  getPayments(): Observable<Payment[]> {
    return this.http.get<Payment[]>(
      `${this.apiUrl}/student/payments`
    );
  }
}
