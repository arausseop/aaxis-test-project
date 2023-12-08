/**
 * User Model.
 */

export class User {

  _id?;
  name = '';
  email = '';
  username = '';
  emailVerified = false;
  profileImage = '';

  subscribeToUpdates = true;
  isAdmin = false;
  active = false;

  constructor(data = {}) {

    Object.assign(this, data);

  }

}
