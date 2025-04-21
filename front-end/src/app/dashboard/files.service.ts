// src/app/services/files.service.ts

import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class FilesService {
  private apiUrl = 'http://localhost:8000?c=Files&m='; // AJUSTADO a tu estructura PHP

  constructor(private http: HttpClient) {}

  private getHeaders() {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      Authorization: `Bearer ${token}`
    });
  }

  uploadFiles(proyectoId: string, archivos: File[]) {
    const formData = new FormData();
    formData.append('proyectoId', proyectoId);
    archivos.forEach(file => formData.append('archivos', file));

    return this.http.post(`${this.apiUrl}upload&proyectoId=${proyectoId}`, formData, {
      headers: this.getHeaders()
    });
  }

  listFiles(proyectoId: string) {
    const params = new HttpParams().set('proyectoId', proyectoId);
    return this.http.get<string[]>(`${this.apiUrl}list`, {
      headers: this.getHeaders(),
      params
    });
  }

  downloadFile(proyectoId: string, filename: string) {
    const params = new HttpParams()
      .set('proyectoId', proyectoId)
      .set('filename', filename);

    return this.http.get(`${this.apiUrl}download`, {
      headers: this.getHeaders(),
      params,
      responseType: 'blob'
    });
  }

  deleteFile(proyectoId: string, filename: string) {
    const body = { proyectoId, filename };
    return this.http.request('delete', `${this.apiUrl}delete`, {
      headers: this.getHeaders(),
      body
    });
  }
}