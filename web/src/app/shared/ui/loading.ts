import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProgressSpinnerModule } from 'primeng/progressspinner';

@Component({
  selector: 'app-ui-loading',
  standalone: true,
  imports: [CommonModule, ProgressSpinnerModule],
  template: `
    @if (visible) {
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        
        <p-progressSpinner 
          styleClass="w-16 h-16" 
          strokeWidth="4" 
          animationDuration=".5s">
        </p-progressSpinner>

      </div>
    }
  `
})
export class Loading {
  // Input para controlar si se muestra u oculta desde el padre
  @Input() visible = false;
}