import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ProductComponent } from './product.component';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductShowComponent } from './product-show/product-show.component';
import { ProductNewComponent } from './product-new/product-new.component';

const productRoutes: Routes = [
  {
    path: '',
    component: ProductComponent,
    children: [
      {
        path: 'list',
        component: ProductListComponent,
      },
      {
        path: 'new',
        component: ProductNewComponent,
      },
      {
        path: 'show/:id',
        component: ProductShowComponent,
      },
      {
        path: '',
        redirectTo: 'list',
        pathMatch: 'prefix',
      },
    ],
  }
];

@NgModule({
  imports: [RouterModule.forChild(productRoutes)],
  exports: [RouterModule]
})
export class ProductsModuleRoutingModule { }
export const routedComponents = [
  ProductListComponent,
  ProductNewComponent,
  ProductShowComponent,
];
