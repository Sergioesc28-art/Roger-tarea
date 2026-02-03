import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TeacherService } from '../../../services/teacher.service';
import { Course } from '../../../models/api.models';

@Component({
  selector: 'app-teacher-schedule',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './teacher-schedule.component.html',
  styleUrls: ['./teacher-schedule.component.css']
})
export class TeacherScheduleComponent implements OnInit {
  schedule = signal<Course[]>([]);
  loading = signal(true);

  // Días de la semana para organizar el horario
  daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

  constructor(private teacherService: TeacherService) {}

  ngOnInit(): void {
    this.loadSchedule();
  }

  loadSchedule(): void {
    this.loading.set(true);
    this.teacherService.getSchedule().subscribe({
      next: (response) => {
        this.schedule.set(response.data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error al cargar horario:', err);
        this.loading.set(false);
      }
    });
  }

  getCoursesByDay(day: string): Course[] {
    return this.schedule().filter(course => course.horario.dia === day);
  }
}
