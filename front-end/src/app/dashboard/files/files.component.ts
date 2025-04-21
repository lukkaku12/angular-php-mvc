import { Component, OnInit } from '@angular/core';
import { FilesService } from '../files.service';
import { NgFor } from '@angular/common';

@Component({
  selector: 'app-files',
  imports: [NgFor],
  templateUrl: './files.component.html',
  styleUrl: './files.component.css'
})
export class FilesComponent implements OnInit {
  archivos: string[] = [];
  archivosSeleccionados: File[] = [];
  proyectoId: string = '1'; // Simulado. Cámbialo según tu lógica.

  constructor(private filesService: FilesService) {}

  ngOnInit() {
    this.cargarArchivos();
  }

  onFileSelected(event: any) {
    this.archivosSeleccionados = Array.from(event.target.files);
  }

  subirArchivos() {
    this.filesService.uploadFiles(this.proyectoId, this.archivosSeleccionados)
      .subscribe(() => {
        alert('Archivos subidos correctamente');
        this.archivosSeleccionados = [];
        this.cargarArchivos();
      });
  }

  cargarArchivos() {
    this.filesService.listFiles(this.proyectoId)
      .subscribe(files => {
        this.archivos = files;
      });
  }

  descargar(nombreArchivo: string) {
    this.filesService.downloadFile(this.proyectoId, nombreArchivo)
      .subscribe(blob => {
        const a = document.createElement('a');
        const url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = nombreArchivo;
        a.click();
        window.URL.revokeObjectURL(url);
      });
  }

  eliminar(nombreArchivo: string) {
    if (!confirm('¿Seguro que deseas eliminar este archivo?')) return;

    this.filesService.deleteFile(this.proyectoId, nombreArchivo)
      .subscribe(() => {
        alert('Archivo eliminado');
        this.cargarArchivos();
      });
  }
}
