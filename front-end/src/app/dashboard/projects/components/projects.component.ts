import { Component, OnInit } from "@angular/core";
import { DashboardService } from "../../dashboard.service";
import { Project } from "../interfaces/projects.interface";
import { DatePipe, NgFor, NgIf } from "@angular/common";
import { Router } from "@angular/router";

@Component({
  selector: "app-projects",
  imports: [NgIf, NgFor, DatePipe],
  templateUrl: "../pages/projects.component.html",
  styleUrl: "../projects.component.css",
})
export class ProjectsComponent implements OnInit {
  public userId: number = Number(localStorage.getItem("userId"));
  public ProjectsOwnedByUser: Project[] = [];

  constructor(
    private dashboardService: DashboardService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.userProjects();
  }

  public userProjects() {
    this.dashboardService
      .getUserProjects(this.userId)
      .subscribe((arg) => (this.ProjectsOwnedByUser = arg));
  }

  public goCreateProject() {
    this.router.navigate(['dashboard/projects/create']);
  }

  public deleteProject(projectId: number) {
    //pendiente por conectarme con el endpoint de eliminar proyectos, hacer observable.
    this.dashboardService.deleteProject(projectId).subscribe()
    this.userProjects()
  }

  public goEditProject(projectId: number) {
    //pendiente por conectarme con el endpoint de eliminar proyectos, hacer observable.
    this.router.navigate(['dashboard/projects/update',projectId])
  }

  verArchivos(projectId: number) {
    this.router.navigate(['/dashboard/projects', projectId, 'files']);
  }
}
