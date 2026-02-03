import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { StudentService } from '../../../services/student.service';
import { Course } from '../../../models/api.models';

@Component({
  selector: 'app-student-courses',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './student-courses.component.html',
  styleUrls: ['./student-courses.component.css']
})
export class StudentCoursesComponent implements OnInit {
  courses = signal<Course[]>([]);
  loading = signal(true);
  enrolling = signal<number | null>(null);
  message = signal<{ type: 'success' | 'error', text: string } | null>(null);

  constructor(private studentService: StudentService) {}

  ngOnInit(): void {
    this.loadCourses();
  }

  loadCourses(): void {
    this.loading.set(true);
    this.studentService.getCourses().subscribe({
      next: (response) => {
        this.courses.set(response.data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error al cargar cursos:', err);
        this.loading.set(false);
      }
    });
  }

  enrollCourse(courseId: number): void {
    this.enrolling.set(courseId);
    this.message.set(null);

    this.studentService.enrollCourse(courseId).subscribe({
      next: (response) => {
        this.message.set({ type: 'success', text: response.message });
        this.enrolling.set(null);
        // Recargar cursos para actualizar cupos
        this.loadCourses();
      },
      error: (err) => {
        this.message.set({ 
          type: 'error', 
          text: err.error?.message || 'Error al inscribirse' 
        });
        this.enrolling.set(null);
      }
    });
  }
}
