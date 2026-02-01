import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';

// PrimeNG Imports
import { ChartModule } from 'primeng/chart';
import { ButtonModule } from 'primeng/button';
import { TableModule } from 'primeng/table';
import { AvatarModule } from 'primeng/avatar';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule, ChartModule, ButtonModule, TableModule, AvatarModule],
  templateUrl: './dashboard.html'
})
export class Dashboard implements OnInit {
  
  // Datos simulados (luego conectaremos con API)
  stats = [
    { title: 'Alumnos', value: '1,240', icon: 'pi pi-users', color: '#0071e3', increase: '+12%' },
    { title: 'Docentes', value: '45', icon: 'pi pi-briefcase', color: '#BF40BF', increase: '+5%' },
    { title: 'Clases Hoy', value: '28', icon: 'pi pi-calendar', color: '#FF9500', increase: 'Stable' },
    { title: 'Ingresos', value: '$45k', icon: 'pi pi-wallet', color: '#34C759', increase: '+8%' }
  ];

  chartData: any;
  chartOptions: any;

  recentStudents = [
    { name: 'Sofía Vergara', career: 'Derecho', status: 'Inscrito' },
    { name: 'Jorge López', career: 'Ingeniería', status: 'Pendiente' },
    { name: 'Maria K.', career: 'Arquitectura', status: 'Inscrito' },
  ];

  ngOnInit() {
    this.initChart();
  }

  initChart() {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = 'var(--text-muted)'; // Usamos nuestras variables CSS

    this.chartData = {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Nuevos Ingresos',
          data: [65, 59, 80, 81, 56, 95],
          fill: true,
          borderColor: '#0071e3',
          backgroundColor: 'rgba(0, 113, 227, 0.1)', // Azul Apple transparente
          tension: 0.4,
          pointBackgroundColor: '#0071e3',
          pointRadius: 0,
          pointHoverRadius: 6
        }
      ]
    };

    this.chartOptions = {
      plugins: {
        legend: { display: false } // Minimalista
      },
      scales: {
        x: { 
          ticks: { color: textColor }, 
          grid: { display: false } 
        },
        y: { 
          ticks: { color: textColor }, 
          grid: { color: 'var(--glass-border)', drawBorder: false } 
        }
      }
    };
  }
}