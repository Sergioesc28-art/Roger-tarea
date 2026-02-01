import { Routes } from '@angular/router';
import { authGuard } from './core/auth.guard';

export const routes: Routes = [
  // Redirección inicial
  { path: '', redirectTo: 'auth/login', pathMatch: 'full' },

  // Layout de Autenticación (Público)
  {
    path: 'auth',
    loadComponent: () => import('./layout/auth-layout/auth-layout').then(m => m.AuthLayout),
    children: [
      { 
        path: 'login', 
        loadComponent: () => import('./features/auth/login/login').then(m => m.Login) 
      }
    ]
  },

  // Layout Principal (Privado - Requiere Login)
  {
    path: '',
    loadComponent: () => import('./layout/main-layout/main-layout').then(m => m.MainLayout),
    canActivate: [authGuard],
    children: [
      {
        path: 'admin',
        canActivate: [authGuard],
        data: { roles: ['admin'] },
        loadChildren: () => import('./features/admin/admin.routes').then(m => m.ADMIN_ROUTES)
      },
      {
        path: 'teacher',
        canActivate: [authGuard],
        data: { roles: ['docente'] },
        loadChildren: () => import('./features/teacher/teacher.routes').then(m => m.TEACHER_ROUTES)
      },
      {
        path: 'student',
        canActivate: [authGuard],
        data: { roles: ['alumno'] },
        loadChildren: () => import('./features/student/student.routes').then(m => m.STUDENT_ROUTES)
      }
    ]
  },

  { path: '**', redirectTo: 'auth/login' }
];