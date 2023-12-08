import { AfterViewInit, Component, ViewChild } from '@angular/core';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { ProductService } from 'src/app/core-module/services/product.service';

import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ProductNewComponent } from '../product-new/product-new.component';
import { Product } from 'src/app/auth-module/product.model';
import { ProductShowComponent } from '../product-show/product-show.component';
import * as _ from 'lodash';

export interface ProductElement {
  productName: string;
  sku: string;
  description?: string;
}

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.scss']
})
export class ProductListComponent implements AfterViewInit {
  displayedColumns: string[] = ['position', 'productName', 'sku', 'actions'];
  listProductsDataSource = new MatTableDataSource<ProductElement>();

  private currentProductSku: string;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  constructor(
    public productService: ProductService,
    public dialog: MatDialog
  ) {

  }

  openNewFormDialog(): void {
    const dialogRef = this.dialog.open(ProductNewComponent, {
      width: '70%',
      data: {}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed', result);
      // this.animal = result;
    });
  }

  openShowFormDialog($productSku): void {
    console.log($productSku);
    this.currentProductSku = $productSku;
    const dialogRef = this.dialog.open(ProductShowComponent, {
      width: '70%',
      data: {
        productSku: $productSku
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      this.updateDatasourseProduct(result);
      console.log('The dialog was closed', result);

    });
  }

  ngAfterViewInit() {
    this.listProductsDataSource.data = this.productService.products.getValue();
    this.listProductsDataSource.paginator = this.paginator;
    console.log('this.listProductsDataSource.data', this.listProductsDataSource.data);
  }

  updateDatasourseProduct(product) {

    this.listProductsDataSource.data = _.map(this.listProductsDataSource.data, (productData) => {
      let tmpProduct = productData.sku === this.currentProductSku ? product : productData
      return tmpProduct;
    })
  }


  deleteProductForm($productSku) {
    this.productService.delete(this, $productSku);
    this.listProductsDataSource.data = this.listProductsDataSource.data.filter(
      (p: ProductElement, k) => p.sku !== $productSku,
    )
    console.log('delete');
  }
}
