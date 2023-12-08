import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import {
  LoginComponent,
} from './auth-module';

import { AuthGuard } from './core-module/guards';
import { ProductService, UserService } from './core-module/services';

const routes: Routes = [
  {
    path: 'auth/login',
    component: LoginComponent
  },
  {
    path: 'products',
    canActivate: [AuthGuard],
    resolve: {
      products: ProductService
    },
    loadChildren: () => import('./products-module/products.module')
      .then(m => m.ProductsModule),
  },
  {
    path: '**',
    redirectTo: 'products'
  }
];

// configures NgModule imports and exports
@NgModule({
  imports: [RouterModule.forRoot(routes, { relativeLinkResolution: 'legacy' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
