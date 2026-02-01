import { Injectable, inject, signal, computed } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from '../../environments/environment';
import { tap } from 'rxjs/operators';
import { jwtDecode } from 'jwt-decode';

// Interfaz para el Token decodificado (según lo que te devuelve Laravel)
interface DecodedToken {
  sub: number;
  role?: string; // Asegúrate que tu Laravel mande el rol dentro del token o úsalo del objeto user
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private http = inject(HttpClient);
  private router = inject(Router);
  private apiUrl = environment.apiUrl;

  // Signal para el estado del usuario (Reactivo)
  // null = no logueado, objeto = logueado
  currentUser = signal<any>(this.getUserFromStorage());

  // Signal computada: ¿Está logueado? True/False
  isLoggedIn = computed(() => !!this.currentUser());

  // Signal computada: Obtener rol actual
  currentRole = computed(() => this.currentUser()?.rol || '');

  login(credentials: { email: string; password: string }) {
    return this.http.post<any>(`${this.apiUrl}/login`, credentials).pipe(
      tap(response => {
        if (response.token) {
          // 1. Guardar en LocalStorage
          localStorage.setItem('token', response.token);
          
          // 2. Guardar el usuario (Laravel nos devuelve el objeto 'user' limpio)
          localStorage.setItem('user', JSON.stringify(response.user));
          
          // 3. Actualizar la señal
          this.currentUser.set(response.user);
        }
      })
    );
  }

  logout() {
    // Limpieza total
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.currentUser.set(null);
    this.router.navigate(['/auth/login']);
  }

  // Utilidad para recuperar datos al recargar la página (F5)
  private getUserFromStorage() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }
}