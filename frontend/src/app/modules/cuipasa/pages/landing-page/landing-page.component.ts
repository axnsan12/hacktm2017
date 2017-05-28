import {Component, OnInit} from '@angular/core';
import {MagicModel} from '../../models/magic-model';

@Component({
  selector: 'cuipasa-landing-page',
  templateUrl: './landing-page.component.html',
  styleUrls: ['./landing-page.component.scss']
})
export class LandingPageComponent implements OnInit {

  public magicData: MagicModel = null;

  constructor() {
  }

  ngOnInit() {
  }

  public magicDone($event) {
    this.magicData = $event;
  }

}
