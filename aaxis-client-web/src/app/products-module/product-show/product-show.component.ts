import { Component, Inject, Input, OnInit } from '@angular/core';
import { FormGroup, FormControl, FormBuilder, Validators, FormArray } from '@angular/forms';
import { AlertService, ApiService, UtilService, UserService, AuthService, ProductService } from '../../core-module/services';
import * as _ from "lodash";

import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';
import { Product } from 'src/app/auth-module/product.model';

export interface ProductElement {
  productName: string;
  sku: string;
  description?: string;
}

@Component({
  selector: 'app-product-show',
  templateUrl: './product-show.component.html',
  styleUrls: ['./product-show.component.scss']
})

export class ProductShowComponent implements OnInit {

  @Input() productSku: any;

  displayedColumns: string[] = ['productName', 'sku', 'actions'];
  productDataSource = new MatTableDataSource<ProductElement>();

  showAddButton: boolean = true;
  showSaveArrayButton: boolean = true;
  sigleSaveButtonDisableControl: boolean = false;
  arraySaveButtonDisableControl: boolean = false;

  productFormType: FormGroup;
  productsFormType: FormArray;

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: any,
    private formBuilder: FormBuilder,
    private productService: ProductService,
    public readonly dialogRef: MatDialogRef<ProductShowComponent>

  ) {

    this.productService.get(this, this.data.productSku);
  }

  ngOnInit(): void {
    console.log('this.productSku', this.productSku)
    this.setupArrayProductForms();
    this.setupSingleProductForms();
  }

  setupArrayProductForms() {
    this.productsFormType = this.formBuilder.array([]);
  }

  setupSingleProductForms() {
    const product = this.productService.currentProduct.getValue();
    console.log('product', product);

    this.productFormType = new FormGroup({
      productName: new FormControl('', [Validators.required]),
      sku: new FormControl('', [Validators.required]),
      description: new FormControl('')
    });
  }

  showProductForm(getProduct) {
    let pdata = _.pick(getProduct.getValue(), ['productName', 'sku', 'description']);
    this.productFormType.setValue(pdata);
  }

  addProductToArrayForm() {
    this.sigleSaveButtonDisableControl = true;
    this.productsFormType.push(this.productFormType);
    this.productDataSource.data = [this.productFormType.value, ...this.productDataSource.data];

    this.sigleSaveButtonDisableControl = !this.sigleSaveButtonDisableControl ?? true;

    this.setupSingleProductForms();
  }



  dialogClose(data = null) {
    console.log('objectClose');
    if (data) {
      this.setupSingleProductForms();
      this.setupArrayProductForms();
    }
    this.dialogRef.close(data);
  }

  editProduct() {
    console.log(this.productSku);
    this.productService.put(this, this.data.productSku, this.productFormType.value);
  }
}
