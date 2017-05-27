import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomePageComponent} from './pages/home-page/home-page.component';
import {ContactPageComponent} from './pages/contact-page/contact-page.component';
import {ApiService} from './services/api.service';

import {HeaderComponent} from './components/header/header.component';

import {MagicComponent} from './components/magic/magic.component';
import {StaticInfoService} from './services/static-info.service';
import {MockStaticInfoService} from './services/mock-static-info.service';
import { ServiceSelectorComponent } from './components/magic/magic-components/service-selector/service-selector.component';
import { CharacteristicsComponent } from './components/magic/magic-components/characteristics/characteristics.component';
import { ResultsComponent } from './components/magic/magic-components/results/results.component';


@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    HomePageComponent,
    ContactPageComponent,
    HeaderComponent,
    MagicComponent,
    ServiceSelectorComponent,
    CharacteristicsComponent,
    ResultsComponent
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
