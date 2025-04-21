import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { map, Observable, tap } from "rxjs";
import { Project } from "./projects/interfaces/projects.interface";

@Injectable({
  providedIn: "root",
})
export class DashboardService {
  private readonly baseUrl = "http://localhost:8000";

  constructor(private http: HttpClient) {}

  //obtener proyectos del usuario
  getUserProjects(userId: number): Observable<Project[]> {
    const token = localStorage.getItem("token");

    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    });
    return this.http
      .get<Project[]>(
        `${this.baseUrl}?c=Projects&m=getUserProjects&id=${userId}`, {headers}
      )
      .pipe(
        tap((projects) => {
          console.log('Respuesta original del backend:', projects);
        }),
        map((projects) => {
          return projects.map((p) => {
            const project: Project = {
              id: Number(p.id),
              titulo: String(p.titulo),
              descripcion: String(p.descripcion),
              fecha_inicio: new Date(p.fecha_inicio),
              fecha_entrega: new Date(p.fecha_entrega),
              estado: String(p.estado),
              user_id: Number(p.user_id),
              created_at: new Date(p.created_at),
            };
            return project;
          })}
        )
      );
  }

  createProject(project: Project): Observable<{ success: boolean }> {
    const token = localStorage.getItem("token");

    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    });
    return this.http.post<{ success: boolean }>(
      `${this.baseUrl}?c=Projects&m=createProject`,
      {
        titulo: project.titulo,
        descripcion: project.descripcion,
        fecha_inicio: project.fecha_inicio,
        fecha_entrega: project.fecha_entrega,
        estado: project.estado,
        user_id: localStorage.getItem("userId"),
      },
      { headers }
    );
  }

  deleteProject(projectId: number) {
    const token = localStorage.getItem("token");

    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    });
    return this.http.delete(`${this.baseUrl}?c=Projects&m=deleteProject&id=${projectId}`, { headers });

  }

  public editProject() {

  }

  getProjectById(id: number): Observable<Project> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders({ Authorization: `Bearer ${token}` });
  
    return this.http.get<Project>(`${this.baseUrl}?c=Projects&m=getProject&id=${id}`, { headers });
  }
  
  updateProject(project: Project, id: number): Observable<any> {
    const token = localStorage.getItem("token");
    const headers = new HttpHeaders({ Authorization: `Bearer ${token}` });
  
    return this.http.put(`${this.baseUrl}?c=Projects&m=updateProject&id=${id}`, project, { headers });
  }
}
