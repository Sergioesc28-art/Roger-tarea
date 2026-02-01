import { Component, inject, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../core/auth.service';
import { ThemeService } from '../../core/theme.service'; // <--- IMPORTANTE

// PrimeNG
import { ButtonModule } from 'primeng/button';
import { AvatarModule } from 'primeng/avatar';
import { TooltipModule } from 'primeng/tooltip';
import { MenuModule } from 'primeng/menu'; // Para el menú dropdown del usuario si lo usas

@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [
    CommonModule, 
    RouterOutlet, 
    RouterLink, 
    RouterLinkActive,
    ButtonModule,
    AvatarModule,
    TooltipModule,
    MenuModule
  ],
  templateUrl: './main-layout.html'
})
export class MainLayout {
  private auth = inject(AuthService);
  public themeService = inject(ThemeService); // Público para usarlo en el HTML

  user = this.auth.currentUser;
  role = this.auth.currentRole;

  // ... (El código de menuItems se queda igual que antes) ...
  menuItems = computed(() => {
    // ... copia la lógica de menús que ya tenías ...
    const rol = this.role();
    if (rol === 'admin') {
        return [
          { label: 'Dashboard', icon: 'pi pi-home', route: '/admin/dashboard' },
          { label: 'Alumnos', icon: 'pi pi-users', route: '/admin/students' },
          { label: 'Docentes', icon: 'pi pi-briefcase', route: '/admin/teachers' },
          { label: 'Clases', icon: 'pi pi-calendar', route: '/admin/courses' },
        ];
      } else if (rol === 'docente') {
          return [
            { label: 'Horario', icon: 'pi pi-calendar', route: '/teacher/schedule' },
            { label: 'Calificaciones', icon: 'pi pi-check-square', route: '/teacher/grades' },
          ];
      } else {
          return [
            { label: 'Kardex', icon: 'pi pi-book', route: '/student/kardex' },
            { label: 'Inscripción', icon: 'pi pi-plus', route: '/student/enrollment' },
            { label: 'Pagos', icon: 'pi pi-wallet', route: '/student/payments' },
          ];
      }
  });

  logout() {
    this.auth.logout();
  }
}