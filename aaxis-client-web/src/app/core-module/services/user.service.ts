import { Injectable } from '@angular/core';
import { Resolve } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

import { User } from '../../auth-module/user.model';
import { AlertService } from './alerts.service';
import { ApiService } from './api.service';

@Injectable()
export class UserService implements Resolve<Observable<any>> {

  public currentUser = new BehaviorSubject<User>(new User(null));
  private loading = true;

  constructor(private apiService: ApiService,
    private alertService: AlertService) {

  }

  resolve(): Observable<any> {

    if (this.currentUser.getValue().username) {
      return Observable.create((observer) => {

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
    console.log('username', username);
    if (!username) {
      this.apiService.authenticationFailEvent.emit(401);
      return this.currentUser.asObservable();
    }

    return new Observable((observer) => {

      this.apiService.get(`/products`)
        .subscribe(data => {

          this.loading = false;
          this.currentUser.next(new User(data));
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
   * Update user.
   * @param context
   * @param user
   */
  patch(context, user) {

    context.loading = true;
    context.userForm.disable();
    delete user.email;

    this.apiService.patch(`/users/${user._id}`, user)
      .subscribe(data => {

        context.loading = false;
        this.currentUser.next(new User(data));
        context.userForm.enable();
        this.alertService.success('Changes Saved.');

      }, error => {

        context.loading = false;
        context.userForm.enable();
        console.log(error);
        this.alertService.error(error);

      });
  }

}
