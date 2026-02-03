import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AdminService } from '../../../services/admin.service';
import { RegisterStudentRequest } from '../../../models/api.models';

@Component({
  selector: 'app-admin-register-student',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './admin-register-student.component.html',
  styleUrls: ['./admin-register-student.component.css']
})
export class AdminRegisterStudentComponent {
  loading = signal(false);
  message = signal<{ type: 'success' | 'error', text: string } | null>(null);
  
  student = signal<RegisterStudentRequest>({
    email: '',
    password: '',
    matricula: '',
    curp: '',
    first_name: '',
    last_name: '',
    birth_date: '',
    career_id: 1
  });

  careers = signal([
    { id: 1, name: 'Ingeniería en Software' },
    { id: 2, name: 'Ingeniería Industrial' },
    { id: 3, name: 'Administración' }
  ]);

  constructor(private adminService: AdminService) {}

  onSubmit(): void {
    this.loading.set(true);
    this.message.set(null);

    this.adminService.registerStudent(this.student()).subscribe({
      next: (response) => {
        this.message.set({ type: 'success', text: response.message });
        this.loading.set(false);
        // Limpiar formulario
        this.resetForm();
      },
      error: (err) => {
        this.message.set({ 
          type: 'error', 
          text: err.error?.message || 'Error al registrar estudiante' 
        });
        this.loading.set(false);
      }
    });
  }

  resetForm(): void {
    this.student.set({
      email: '',
      password: '',
      matricula: '',
      curp: '',
      first_name: '',
      last_name: '',
      birth_date: '',
      career_id: 1
    });
  }

  updateField(field: keyof RegisterStudentRequest, value: any): void {
    this.student.update(s => ({ ...s, [field]: value }));
  }
}
