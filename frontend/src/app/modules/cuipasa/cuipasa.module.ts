import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomePageComponent} from './pages/home/home-page.component';
import {ContactPageComponent} from './pages/contact-page/contact-page.component';
import {ApiService} from './services/api.service';

import {HeaderComponent} from './components/header/header.component';

import {MagicComponent} from './components/magic/magic.component';
import {StaticInfoService} from './services/static-info.service';
import {MockStaticInfoService} from './services/mock-static-info.service';


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
  providers: [
    ApiService,
    StaticInfoService,
    MockStaticInfoService
  ]
})
export class CuipasaModule {
}
