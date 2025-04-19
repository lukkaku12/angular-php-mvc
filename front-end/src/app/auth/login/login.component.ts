import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../auth.service';
import { Router, RouterModule } from '@angular/router';
import { loginResponse } from './interfaces/login.interface';

@Component({
  selector: 'app-login',
  imports: [FormsModule, HttpClientModule, RouterModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {

  username: string = '';
  password: string = '';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onLogin() {
    this.authService.login(this.username, this.password).subscribe({
      next: (res: loginResponse) => {
        console.log('Token recibido:', res.response);
        localStorage.setItem('token', res.response);
        localStorage.setItem('userId', JSON.stringify(res.user_id));
        this.router.navigate(['/dashboard/projects'])
      },
      error: (err) => {
        console.error('Error al iniciar sesión', err);
        // Mostrar mensaje al usuario
      }
    });
  }

}
