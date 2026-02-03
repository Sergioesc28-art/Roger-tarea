import { Component, computed, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.css']
})
export class LayoutComponent {
  authService = inject(AuthService);
  currentUser = this.authService.currentUser;
  
  userName = computed(() => {
    const user = this.currentUser();
    if (!user) return '';
    
    if (user.perfil_alumno) {
      return `${user.perfil_alumno.first_name} ${user.perfil_alumno.last_name}`;
    } else if (user.perfil_docente) {
      return `${user.perfil_docente.first_name} ${user.perfil_docente.last_name}`;
    }
    return user.email;
  });

  roleName = computed(() => {
    const role = this.currentUser()?.rol;
    if (role === 'admin') return 'Administrador';
    if (role === 'docente') return 'Docente';
    if (role === 'alumno') return 'Alumno';
    return '';
  });

  logout(): void {
    this.authService.logout();
  }
}
