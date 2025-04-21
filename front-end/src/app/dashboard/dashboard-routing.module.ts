import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FilesComponent } from './files/files.component';
import { ProjectsComponent } from './projects/components/projects.component';
import { CreateProjectComponent } from './projects/components/create-project.component';
import { EditProjectComponent } from './projects/components/edit-project.component';

const routes: Routes = [
  {
    path: 'projects',
    component: ProjectsComponent,
  },
  {
    path: 'projects/create',
    component: CreateProjectComponent
  },
  {
    path: 'projects/update/:id',
    component: EditProjectComponent
  },
  {
    path: 'projects/:id/files',
    component: FilesComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DashboardRoutingModule {}