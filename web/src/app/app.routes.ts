import { Routes } from '@angular/router';
import { authGuard } from './guards/auth.guard';
import { LoginComponent } from './components/login/login.component';
import { LayoutComponent } from './components/layout/layout.component';

// Importaciones de componentes de estudiante
import { StudentCoursesComponent } from './components/student/student-courses/student-courses.component';
import { StudentKardexComponent } from './components/student/student-kardex/student-kardex.component';
import { StudentPaymentsComponent } from './components/student/student-payments/student-payments.component';

// Importaciones de componentes de docente
import { TeacherScheduleComponent } from './components/teacher/teacher-schedule/teacher-schedule.component';

export const routes: Routes = [
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: '',
    component: LayoutComponent,
    canActivate: [authGuard],
    children: [
      // Rutas de estudiante
      {
        path: 'student',
        canActivate: [authGuard],
        data: { roles: ['alumno'] },
        children: [
          {
            path: '',
            redirectTo: 'courses',
            pathMatch: 'full'
          },
          {
            path: 'courses',
            component: StudentCoursesComponent
          },
          {
            path: 'kardex',
            component: StudentKardexComponent
          },
          {
            path: 'payments',
            component: StudentPaymentsComponent
          }
        ]
      },
      // Rutas de docente
      {
        path: 'teacher',
        canActivate: [authGuard],
        data: { roles: ['docente'] },
        children: [
          {
            path: '',
            component: TeacherScheduleComponent
          },
          {
            path: 'grades',
            component: TeacherScheduleComponent // Aquí iría el componente de calificaciones
          }
        ]
      },
      // Rutas de administrador
      {
        path: 'admin',
        canActivate: [authGuard],
        data: { roles: ['admin'] },
        children: [
          {
            path: '',
            redirectTo: 'students',
            pathMatch: 'full'
          },
          {
            path: 'students',
            component: StudentCoursesComponent // Aquí iría el componente de admin
          },
          {
            path: 'courses',
            component: StudentCoursesComponent // Aquí iría el componente de crear clases
          }
        ]
      }
    ]
  },
  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  },
  {
    path: '**',
    redirectTo: '/login'
  }
];
