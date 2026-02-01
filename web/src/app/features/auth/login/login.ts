import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';

// UI Components
import { CardModule } from 'primeng/card';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { PasswordModule } from 'primeng/password';
import { MessageModule } from 'primeng/message';
import { CheckboxModule } from 'primeng/checkbox'; // Opcional

// Nuestro componente de carga reutilizable
import { Loading } from '../../../shared/ui/loading';
import { AuthService } from '../../../core/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    CommonModule, 
    ReactiveFormsModule, 
    CardModule, 
    InputTextModule, 
    ButtonModule, 
    PasswordModule,
    MessageModule,
    Loading
  ],
  templateUrl: './login.html'
})
export class Login {
  private fb = inject(FormBuilder);
  private auth = inject(AuthService);
  private router = inject(Router);

  isLoading = signal(false);
  errorMsg = signal('');

  loginForm = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', [Validators.required]]
  });

  onSubmit() {
    if (this.loginForm.invalid) return;

    this.isLoading.set(true);
    this.errorMsg.set('');

    const creds = this.loginForm.getRawValue();

    // Hacemos cast a los tipos correctos para evitar error de TypeScript
    this.auth.login({ 
      email: creds.email!, 
      password: creds.password! 
    }).subscribe({
      next: (response) => {
        // Obtenemos el rol directamente del servicio actualizado
        const role = response.user.rol;
        this.isLoading.set(false);
        this.redirectUser(role);
      },
      error: (err) => {
        this.isLoading.set(false);
        this.errorMsg.set('Credenciales inválidas o error de conexión.');
      }
    });
  }

  private redirectUser(role: string) {
    if (role === 'admin') this.router.navigate(['/admin/dashboard']);
    else if (role === 'docente') this.router.navigate(['/teacher/schedule']);
    else if (role === 'alumno') this.router.navigate(['/student/kardex']);
    else this.router.navigate(['/auth/login']);
  }
}