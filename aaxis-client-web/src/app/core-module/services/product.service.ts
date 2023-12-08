import { Injectable } from '@angular/core';
import { Resolve } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

import { User } from '../../auth-module/user.model';
import { AlertService } from './alerts.service';
import { ApiService } from './api.service';
import { Product } from 'src/app/auth-module/product.model';
import * as _ from 'lodash'


@Injectable()
export class ProductService implements Resolve<Observable<any>> {

  public currentProducts = new BehaviorSubject<Product[]>([]);
  public currentProduct = new BehaviorSubject<Product>(new Product(null));
  public products = new BehaviorSubject<Product[]>([]);
  public currentUser = new BehaviorSubject<User>(new User(null));
  private loading = true;

  constructor(private apiService: ApiService,
    private alertService: AlertService) {

  }

  resolve(): Observable<any> {

    if (this.currentUser.getValue().username) {
      return new Observable((observer) => {

        observer.next(this.currentUser.getValue());
        observer.complete();

      });
    } else {
      return this.load();
    }
  }

  load(): Observable<any> {

    this.loading = true;
    const username = localStorage.getItem('username');

    if (!username) {
      this.apiService.authenticationFailEvent.emit(401);
      return this.currentUser.asObservable();
    }

    return new Observable((observer) => {

      this.apiService.get(`/products`)
        .subscribe(data => {

          this.loading = false;
          this.products.next(_.map(data.items, (product) => new Product(product)));
          observer.next(this.currentUser.getValue());
          observer.complete();

        }, error => {

          this.apiService.authenticationFailEvent.emit(401);
          observer.complete();

        });

    });

  }

  set(data) {

    localStorage.setItem('username', data.username);
    localStorage.setItem('email', data.email);
    localStorage.setItem('name', data.name);
    localStorage.setItem('roles', data.roles);

    this.currentUser.next(new User(data));
  }


  reset() {
    this.currentUser.next(new User({}));
  }


  /**
   * Partial Update product.
   * @param context
   * @param product
   */
  get(context, productSku: string) {

    context.loading = true;
    // context.productForm.disable();

    this.apiService.get(`/products/${productSku}`)
      .subscribe(data => {

        context.loading = false;
        this.currentProduct.next(new Product(data));

        context.showProductForm(this.currentProduct);

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

  /**
   * Partial Update product.
   * @param context
   * @param product
   */
  patch(context, productSku: string, product: Product) {

    context.loading = true;
    // context.productForm.disable();

    this.apiService.put(`/products/${productSku}`, product)
      .subscribe(data => {

        context.loading = false;
        this.currentProduct.next(new Product(data));
        // context.productForm.enable();
        this.alertService.success('Changes Saved.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

  /**
   * Update product.
   * @param context
   * @param product
   */
  put(context, productSku: string, product: Product) {

    context.loading = true;

    this.apiService.put(`/products/${productSku}`, product)
      .subscribe(data => {

        context.loading = false;
        this.currentProduct.next(new Product(data));
        context.dialogClose(data);
        this.alertService.success('Changes Saved.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

  /**
   * Crete Product.
   * @param context
   * @param product
   */
  post(context, product: Product) {

    context.loading = true;

    this.apiService.post(`/products`, product)
      .subscribe(data => {

        context.loading = false;
        this.currentProduct.next(new Product(data));
        context.dialogClose(data);
        this.alertService.success('Changes Saved.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

  /**
   * Create array of products.
   * @param context
   * @param products
   */
  postArray(context, products: Product[]) {

    context.loading = true;

    this.apiService.post(`/products/bulk/create`, products)
      .subscribe(data => {

        this.currentProducts.next(_.map(data.items, (product) => new Product(product)));
        context.dialogClose(data);
        this.alertService.success('Changes Saved.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }


  /**
     * Soft Delete Product.
     * @param context
     * @param product
     */
  delete(context, productSku: string) {

    context.loading = true;

    this.apiService.delete(`/products/${productSku}`)
      .subscribe(data => {

        context.loading = false;

        this.alertService.success('Product Deleted.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

  /**
     * Physical Remove Product.
     * @param context
     * @param product
     */
  remove(context, productSku: string) {

    context.loading = true;
    // context.productForm.disable();

    this.apiService.delete(`/products/${productSku}/remove`)
      .subscribe(data => {

        context.loading = false;
        this.alertService.success('Product Removed.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

}
