import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from './auth.service';

export const authGuard: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  // 1. Verificar si está logueado
  if (!authService.isLoggedIn()) {
    router.navigate(['/auth/login']);
    return false;
  }

  // 2. Verificar Roles (Si la ruta exige roles específicos)
  // Ejemplo: { data: { roles: ['admin'] } }
  const requiredRoles = route.data['roles'] as Array<string>;
  
  if (requiredRoles) {
    const userRole = authService.currentRole(); // Usamos la señal computada
    
    if (!requiredRoles.includes(userRole)) {
      // Usuario logueado pero sin permiso (ej: Alumno queriendo entrar a Admin)
      // Lo redirigimos a su dashboard correspondiente para evitar bucles
      if (userRole === 'admin') router.navigate(['/admin/dashboard']);
      if (userRole === 'docente') router.navigate(['/teacher/schedule']);
      if (userRole === 'alumno') router.navigate(['/student/kardex']);
      
      return false;
    }
  }

  return true;
};