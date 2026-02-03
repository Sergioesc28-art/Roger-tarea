import { Component, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { StudentService } from '../../../services/student.service';
import { Kardex } from '../../../models/api.models';

@Component({
  selector: 'app-student-kardex',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './student-kardex.component.html',
  styleUrls: ['./student-kardex.component.css']
})
export class StudentKardexComponent implements OnInit {
  kardex = signal<Kardex[]>([]);
  loading = signal(true);

  // Calcular promedio general
  averageGrade = computed(() => {
    const grades = this.kardex()
      .map(item => item.calificaciones.final)
      .filter(grade => grade !== null) as number[];
    
    if (grades.length === 0) return null;
    
    const sum = grades.reduce((acc, grade) => acc + grade, 0);
    return (sum / grades.length).toFixed(2);
  });

  // Calcular créditos cursados
  totalCredits = computed(() => {
    return this.kardex()
      .filter(item => item.calificaciones.final !== null)
      .reduce((acc, item) => acc + item.materia.creditos, 0);
  });

  // Calcular materias completadas
  completedSubjects = computed(() => {
    return this.kardex().filter(k => k.calificaciones.final !== null).length;
  });

  constructor(private studentService: StudentService) {}

  ngOnInit(): void {
    this.loadKardex();
  }

  loadKardex(): void {
    this.loading.set(true);
    this.studentService.getKardex().subscribe({
      next: (data) => {
        this.kardex.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error al cargar kardex:', err);
        this.loading.set(false);
      }
    });
  }

  getGradeClass(grade: number | null): string {
    if (grade === null) return '';
    if (grade >= 9) return 'grade-excellent';
    if (grade >= 8) return 'grade-good';
    if (grade >= 7) return 'grade-pass';
    return 'grade-fail';
  }
}
