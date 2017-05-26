import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CuipasaModule} from './modules/cuipasa/cuipasa.module';
import {ContactPageComponent} from './modules/cuipasa/pages/contact-page/contact-page.component';
import {HomePageComponent} from './modules/cuipasa/pages/home/home-page.component';
/**
 * Created by NM on 5/26/2017.
 */



const appRoutes: Routes = [
  { path: '',   redirectTo: '/home', pathMatch: 'full' },
  { path: 'home', component: HomePageComponent},
  { path: 'contact', component: ContactPageComponent}
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
export class AppRoutingModule {}
