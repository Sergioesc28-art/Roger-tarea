import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';

// PrimeNG
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { TagModule } from 'primeng/tag'; // Para los "badges" de estatus
import { TooltipModule } from 'primeng/tooltip';

import { AdminService, Student } from '../admin.service';

@Component({
  selector: 'app-admin-students',
  standalone: true,
  imports: [
    CommonModule, 
    TableModule, 
    ButtonModule, 
    InputTextModule, 
    TagModule,
    TooltipModule
  ],
  templateUrl: './students.html'
})
export class Students implements OnInit {
  private adminService = inject(AdminService);
  
  // Array de alumnos
  students: any[] = [];
  loading = true;

  ngOnInit() {
    this.loadStudents();
  }

  loadStudents() {
    // Simulamos carga de datos (Más adelante conectamos con adminService.getStudents())
    setTimeout(() => {
      this.students = [
        { id: 1, matricula: '2026-001', name: 'Ana Paula Gómez', career: 'Ing. Software', semester: 1, status: 'Activo', email: 'ana@saeko.mx' },
        { id: 2, matricula: '2026-002', name: 'Carlos Mendez', career: 'Derecho', semester: 3, status: 'Pendiente', email: 'carlos@saeko.mx' },
        { id: 3, matricula: '2026-003', name: 'Sofia Vergara', career: 'Arquitectura', semester: 1, status: 'Baja', email: 'sofia@saeko.mx' },
        { id: 4, matricula: '2026-004', name: 'Luis Miguel', career: 'Ing. Software', semester: 7, status: 'Activo', email: 'luis@saeko.mx' },
        { id: 5, matricula: '2026-005', name: 'Maria Jose', career: 'Derecho', semester: 2, status: 'Activo', email: 'majo@saeko.mx' },
      ];
      this.loading = false;
    }, 1000);
  }

  getSeverity(status: string): "success" | "warn" | "danger" | "info" {
    switch (status) {
      case 'Activo': return 'success';     // Verde
      case 'Pendiente': return 'warn';  // Naranja
      case 'Baja': return 'danger';        // Rojo
      default: return 'info';
    }
  }
}