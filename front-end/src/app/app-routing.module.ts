import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { HelloComponent } from './hello/hello.component';

const routes: Routes = [
  { path: '', component: LoginComponent },
  { path: 'hello', component: HelloComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
