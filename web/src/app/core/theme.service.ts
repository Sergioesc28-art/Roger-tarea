import { Injectable, signal, effect, inject } from '@angular/core';
import { DOCUMENT } from '@angular/common';

@Injectable({
  providedIn: 'root' // Singleton: Una única instancia para toda la app
})
export class ThemeService {
  private document = inject(DOCUMENT);
  
  // 1. SIGNAL: Es la variable reactiva. 
  // 'true' = Modo Oscuro, 'false' = Modo Claro.
  // Al iniciar, llama a getInitialTheme() para saber cuál poner.
  isDark = signal<boolean>(this.getInitialTheme());

  constructor() {
    // 2. EFFECT: Esto es MAGIA de Angular nuevo.
    // Se ejecuta AUTOMÁTICAMENTE cada vez que la señal 'isDark' cambia.
    effect(() => {
      const isDark = this.isDark();
      const html = this.document.documentElement; // La etiqueta <html>

      if (isDark) {
        // Agrega la clase 'dark'. Tailwind y nuestro CSS reaccionan a esto.
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark'); // Guardar en memoria
      } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light'); // Guardar en memoria
      }
    });
  }

  // 3. Método público para cambiar el tema (Lo llama el botón del Navbar)
  toggleTheme() {
    this.isDark.update(val => !val); // Invierte el valor (true -> false)
  }

  // 4. Lógica inicial: ¿Qué prefiere el usuario?
  private getInitialTheme(): boolean {
    // A) ¿Ya eligió antes?
    const stored = localStorage.getItem('theme');
    if (stored) return stored === 'dark';
    
    // B) Si es nuevo, ¿qué tema usa su sistema operativo?
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
}