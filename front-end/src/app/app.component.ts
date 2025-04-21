import { Component, OnInit } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet],
  templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {
  constructor(private router: Router) {}
  
  ngOnInit(): void {
    localStorage.setItem('app_active_tab', String(Date.now()));
    window.addEventListener('storage', (event) => {
      if (event.key === 'app_active_tab') {
        localStorage.clear()
        this.router.navigate(['login'])
        alert('La aplicación ya está abierta en otra pestaña.');
      }
    });
  }
  
}
