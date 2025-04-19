import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { loginResponse } from './login/interfaces/login.interface';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private baseUrl = 'http://localhost:8000';

  constructor(private http: HttpClient) {}

  isLoggedIn(): boolean {
    return !!localStorage.getItem('token'); // o cualquier lógica que uses
  }

  login(username: string, password: string): Observable<loginResponse> {
    const url = `${this.baseUrl}?c=Users&m=login`;
    const body = { username, password };
    return this.http.post<loginResponse>(url, body);

  }

  register(username: string, password: string): Observable<any> {
    const url = `${this.baseUrl}?c=Users&m=register`;
    const body = { username, password };
    return this.http.post<loginResponse>(url, body);
  }

  logout() {
    localStorage.removeItem('token');
  }
}