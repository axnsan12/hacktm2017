import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ContactPageComponent} from './pages/contact-page/contact-page.component';

import {HeaderComponent} from './components/header/header.component';

import {MagicComponent} from './components/magic/magic.component';
import {StaticInfoService} from './services/static-info.service';
import {MockStaticInfoService} from './services/mock-static-info.service';
import {ServiceSelectorComponent} from './components/magic/magic-components/service-selector/service-selector.component';
import {CharacteristicsComponent} from './components/magic/magic-components/characteristics/characteristics.component';
import {ResultsComponent} from './components/magic/magic-components/results/results.component';
import {ResultComponent} from './components/magic/magic-components/result/result.component';
import {DataService} from './services/data.service';
import {ContactformComponent} from './components/contactform/contactform.component';
import {LandingPageComponent} from './pages/landing-page/landing-page.component';
import {AppModule} from "../../app.module";
import {LoginmodalComponent} from "../../components/loginmodal/loginmodal.component";


@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    LandingPageComponent,
    ContactPageComponent,
    HeaderComponent,
    MagicComponent,
    ServiceSelectorComponent,
    CharacteristicsComponent,
    ResultsComponent,
    ResultComponent,
    ContactformComponent,
    LoginmodalComponent
  ],
  exports: [LandingPageComponent],
  providers: [
    StaticInfoService,
    MockStaticInfoService,
    DataService
  ]
})
export class CuipasaModule {
}
