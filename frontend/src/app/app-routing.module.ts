import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CuipasaModule} from './modules/cuipasa/cuipasa.module';
import {ContactPageComponent} from './modules/cuipasa/pages/contact-page/contact-page.component';
import {LandingPageComponent} from './modules/cuipasa/pages/landing-page/landing-page.component';
/**
 * Created by NM on 5/26/2017.
 */



const appRoutes: Routes = [
  {path: '', component: LandingPageComponent},
  {path: 'home', component: LandingPageComponent},
  {path: 'contact', component: ContactPageComponent}
];

@NgModule({
  imports: [
    RouterModule.forRoot(appRoutes),
    CuipasaModule
  ],
  exports: [
    RouterModule
  ]
})
export class AppRoutingModule {
}
