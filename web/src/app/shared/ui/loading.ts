import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProgressSpinnerModule } from 'primeng/progressspinner';

@Component({
  selector: 'app-ui-loading',
  standalone: true,
  imports: [CommonModule, ProgressSpinnerModule],
  template: `
    @if (visible) {
      <div class="fixed inset-0 z-[9999] flex items-center justify-center transition-all duration-300"
           style="background: rgba(0,0,0, 0.2); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
        
        <div class="glass-panel p-6 rounded-[24px] flex flex-col items-center gap-4">
          <p-progressSpinner 
            styleClass="w-12 h-12" 
            strokeWidth="5" 
            animationDuration=".8s">
          </p-progressSpinner>
          <span class="text-sm font-medium tracking-wide" style="color: var(--text-main)">Cargando...</span>
        </div>

      </div>
    }
  `
})
export class Loading {
  @Input() visible = false;
}