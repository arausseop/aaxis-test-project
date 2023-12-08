import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, FormBuilder, Validators, FormArray } from '@angular/forms';
import { AlertService, ApiService, UtilService, UserService, AuthService, ProductService } from '../../core-module/services';
import * as _ from "lodash";
import { MatDialogRef } from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';
import { Product } from 'src/app/auth-module/product.model';

@Component({
  selector: 'app-product-new',
  templateUrl: './product-new.component.html',
  styleUrls: ['./product-new.component.scss']
})

export class ProductNewComponent implements OnInit {

  displayedColumns: string[] = ['productName', 'sku', 'actions'];
  productDataSource = new MatTableDataSource<Product>();

  showAddButton: boolean = true;
  showSaveArrayButton: boolean = true;
  sigleSaveButtonDisableControl: boolean = false;
  arraySaveButtonDisableControl: boolean = false;

  productFormType: FormGroup;
  productsFormType: FormArray;


  constructor(
    private formBuilder: FormBuilder,
    private productService: ProductService,
    public readonly dialogRef: MatDialogRef<ProductNewComponent>
  ) { }

  ngOnInit(): void {
    this.setupArrayProductForms();
    this.setupSingleProductForms();
  }

  setupArrayProductForms() {
    this.productsFormType = this.formBuilder.array([]);
  }

  setupSingleProductForms() {
    this.productFormType = new FormGroup({
      productName: new FormControl('', [Validators.required]),
      sku: new FormControl('', [Validators.required]),
      description: new FormControl('')
    });
  }


  addProductToArrayForm() {
    this.sigleSaveButtonDisableControl = true;
    this.productsFormType.push(this.productFormType);
    this.productDataSource.data = [this.productFormType.value, ...this.productDataSource.data];

    this.sigleSaveButtonDisableControl = !this.sigleSaveButtonDisableControl ?? true;

    this.setupSingleProductForms();
  }

  showProductForm($productSku) {

    const selectedProduct = this.productsFormType.at(this.productsFormType.value.findIndex(product => product.sku === $productSku));
    this.setupSingleProductForms();
    this.productFormType.setValue(selectedProduct.value);
    this.showAddButton = !this.showAddButton;
    console.log(selectedProduct);
  }

  removeProductForm($productSku) {

    this.productDataSource.data = this.productDataSource.data.filter(
      (p: Product, k) => p.sku !== $productSku,
    )
    const index = this.productsFormType.value.findIndex(product => product.sku === $productSku)
    this.productsFormType.removeAt(index)
    this.sigleSaveButtonDisableControl = this.productDataSource.data.length > 0 ? true : false;

  }
  editProductOnArrayForm() {
    console.log('aksd')
  }

  dialogClose(data = null) {
    console.log('objectClose');
    if (data) {
      this.setupSingleProductForms();
      this.setupArrayProductForms();
    }
    this.dialogRef.close(data);
  }

  saveProduct() {

    this.productService.post(this, this.productFormType.value);
  }

  saveArrayProducts() {

    this.productService.postArray(this, this.productsFormType.value);
  }
}
