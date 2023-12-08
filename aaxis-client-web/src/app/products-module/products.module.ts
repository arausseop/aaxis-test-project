import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ProductsModuleRoutingModule } from './products-module-routing.module';
import { ProductComponent } from './product.component';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductShowComponent } from './product-show/product-show.component';
import { ProductNewComponent } from './product-new/product-new.component';
import { CoreModule } from '@angular/flex-layout';
import { SharedModule } from '../shared-module';
import { RouterModule } from '@angular/router';


@NgModule({
  declarations: [
    ProductComponent,
    ProductListComponent,
    ProductShowComponent,
    ProductNewComponent
  ],
  imports: [
    CommonModule,
    RouterModule,
    SharedModule,
    CoreModule,
    ProductsModuleRoutingModule
  ],
})
export class ProductsModule { }
