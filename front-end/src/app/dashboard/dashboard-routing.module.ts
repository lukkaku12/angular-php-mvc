import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FilesComponent } from './files/files.component';
import { ProjectsComponent } from './projects/components/projects.component';
import { CreateProjectComponent } from './projects/components/create-project.component';

const routes: Routes = [
  {
    path: 'files',
    component: FilesComponent
  },
  {
    path: 'projects',
    component: ProjectsComponent,
  },
  {
    path: 'projects/create',
    component: CreateProjectComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DashboardRoutingModule {}