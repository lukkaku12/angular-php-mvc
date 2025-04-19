import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { registerResponse } from './interfaces/register.interface';

@Component({
  selector: 'app-register',
  imports: [FormsModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
  username: string = '';
  password: string = '';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onSubmit() {
    if (this.username && this.password) {
      
      this.authService.register(this.username, this.password).subscribe({
            next: (res: registerResponse) => {
              console.log(res.message)
              this.router.navigate(['/auth/login'])
            },
            error: (err) => {
              console.error('Error al crear usuario', err);
              
            }
          })

    } else {
      alert('Por favor, complete todos los campos');
    }
  }
}
