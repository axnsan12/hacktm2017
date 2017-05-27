import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomePageComponent} from './pages/home/home-page.component';
import {ContactPageComponent} from './pages/contact-page/contact-page.component';
import {ApiService} from './services/api.service';

import {HeaderComponent} from './components/header/header.component';

import {MagicComponent} from './components/magic/magic.component';


@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    HomePageComponent,
    ContactPageComponent,
    HeaderComponent,
    MagicComponent
  ],
  exports: [HomePageComponent],
  providers: [ApiService]
})
export class CuipasaModule {
}
