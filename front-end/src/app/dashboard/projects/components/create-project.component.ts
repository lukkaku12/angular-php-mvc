import { Component } from "@angular/core";
import { DashboardService } from "../../dashboard.service";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";

@Component({
  selector: "app-create-project",
  imports: [FormsModule],
  templateUrl: "../pages/create-project.component.html",
  styleUrl: "../create-project.component.css",
})
export class CreateProjectComponent {
  constructor(private dashboardService: DashboardService, private router: Router) {}

  public project = {
    titulo: "",
    descripcion: "",
    fecha_inicio: "",
    fecha_entrega: "",
    estado: "",
    created_at: "",
  };

  onSubmit() {
    this.dashboardService.createProject(this.project).subscribe({
      next: (response) => {
        console.log(response)
        if (response.success) {
          alert("Proyecto creado con éxito");
          // Opcional: limpiar el formulario
          this.project = {
            titulo: "",
            descripcion: "",
            fecha_inicio: "",
            fecha_entrega: "",
            estado: "",
            created_at: "",
          };
          this.router.navigate(['dashboard/projects'])
        } else {
          alert("Hubo un error al crear el proyecto");
        }
      },
      error: (err) => {
        console.error("Error al crear proyecto", err);
        alert("Error de conexión al servidor");
      },
    });
  }
}
