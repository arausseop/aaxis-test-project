import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptorService implements HttpInterceptor {

  constructor(public authService: AuthService) {
  }

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

    request = request.clone({
      setHeaders: {
        'authorization': `Bearer ${this.authService.token || ''}`,
        'client-time': new Date().toISOString(),
        'Access-Control-Allow-Origin': '*',
      }
    });


    return next
      .handle(request)
      .pipe(catchError((error: any) => {

        if (error instanceof HttpErrorResponse) {
          if (error.status === 401) {
            this.authService.signOut();
          }
        }

        return throwError(error);

      }));


  }
}
