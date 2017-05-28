import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';

import {AppComponent} from './app.component';
import {AppRoutingModule} from './app-routing.module';
import {MainMenuComponent} from './components/main-menu/main-menu.component';
import {ModalModule} from 'ngx-bootstrap';
import { LoginmodalComponent } from './components/loginmodal/loginmodal.component';

@NgModule({
  declarations: [
    AppComponent,
    MainMenuComponent,


  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule,
    ModalModule.forRoot()
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
