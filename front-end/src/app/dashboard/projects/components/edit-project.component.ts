import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { DashboardService } from '../../dashboard.service';
import { Project } from '../interfaces/projects.interface';

@Component({
  selector: 'app-edit-project',
  imports: [ReactiveFormsModule],
  templateUrl: '../pages/edit-project.component.html',
  styleUrl: '../edit-project.component.css'
})
export class EditProjectComponent implements OnInit {
  editForm!: FormGroup;
  projectId!: number;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private dashboardService: DashboardService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.projectId = Number(this.route.snapshot.paramMap.get('id'));
    console.log('ID recibido para editar:', this.projectId);

    this.editForm = this.fb.group({
      titulo: ['', Validators.required],
      descripcion: [''],
      fecha_inicio: ['', Validators.required],
      fecha_entrega: ['', Validators.required],
      estado: ['', Validators.required]
    });

    this.dashboardService.getProjectById(this.projectId).subscribe((project) => {
      this.editForm.patchValue({
        titulo: project.titulo,
        descripcion: project.descripcion,
        fecha_inicio: project.fecha_inicio,
        fecha_entrega: project.fecha_entrega,
        estado: project.estado
      });
    });
  }

  onSubmit(): void {
    if (this.editForm.valid) {
      const updatedProject: Project = {
        id: this.projectId,
        ...this.editForm.value,
        user_id: Number(localStorage.getItem('userId')), // o como lo manejes
        created_at: new Date() // si lo necesitás
      };

      this.dashboardService.updateProject(updatedProject, this.projectId).subscribe(() => {
        this.router.navigate(['/dashboard/projects']);
      });
    }
  }
}
