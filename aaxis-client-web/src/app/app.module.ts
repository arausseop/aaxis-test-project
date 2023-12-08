/***
 * App Module File.
 *
 * All angular dependencies, app feature-module, app services, app module & external libraries
 * are here.
 */

import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

/************************************************************************************
 * External libraries
 *************************************************************************************/



/************************************************************************************
 * App Routing & Environment.
 *************************************************************************************/

import { AppRoutingModule } from './app-routing.module';

/************************************************************************************
 * App Components
 *************************************************************************************/

import { AppComponent } from './app.component';
import {
  HeaderComponent,
} from './feature-module';

/************************************************************************************
 * App Services.
 *************************************************************************************/


/************************************************************************************
 * App Modules
 *************************************************************************************/

import { AuthModule } from './auth-module/auth.module';
import { CoreModule } from './core-module';
import { SharedModule } from './shared-module/shared.module';

/************************************************************************************
 * App Module
 *************************************************************************************/

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    CoreModule,
    SharedModule,
    AuthModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
