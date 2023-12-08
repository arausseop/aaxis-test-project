/**
 * User Model.
 */

export class Product {

  sku: string = '';
  productName: string = '';
  description?: string = '';
  createdAt?: string = '';
  updatedAt?: string = '';
  deletedAt?: string = '';

  constructor(data = {}) {

    Object.assign(this, data);

  }

}
