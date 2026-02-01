import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter, withViewTransitions } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { provideHttpClient, withFetch, withInterceptors } from '@angular/common/http';

// PrimeNG v18 Config
import { providePrimeNG } from 'primeng/config';
import Aura from '@primeuix/themes/aura';

import { routes } from './app.routes';
import { authInterceptor } from './core/auth.interceptor'; // Lo crearemos abajo

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(routes),
    
    // Router con transiciones de vista nativas del navegador
    provideRouter(routes, withViewTransitions()),
    
    // HTTP con Fetch y nuestro Interceptor de JWT
    provideHttpClient(
      withFetch(), 
      withInterceptors([authInterceptor])
    ),
    
    // Animaciones
    provideAnimationsAsync(),
    
    // Tema Aura de PrimeNG
    providePrimeNG({
        theme: {
            preset: Aura,
            options: {
                darkModeSelector: '.dark',
            }
        },
        ripple: true
    })
  ]
};