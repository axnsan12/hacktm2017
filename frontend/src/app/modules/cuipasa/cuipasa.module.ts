import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomePageComponent} from './pages/home/home-page.component';
import {ContactPageComponent} from './pages/contact-page/contact-page.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    HomePageComponent,
    ContactPageComponent
  ],
  exports: [HomePageComponent]
})
export class CuipasaModule {
}
