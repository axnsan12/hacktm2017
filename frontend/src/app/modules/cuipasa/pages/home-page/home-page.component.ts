import {Component, OnInit} from '@angular/core';

@Component({
  selector: 'cuipasa-home',
  templateUrl: './home-page.component.html',
  styleUrls: ['./home-page.component.scss']
})
export class HomePageComponent implements OnInit {

  constructor() {
  }

  ngOnInit() {
  }

  public magicDone($event) {
    console.log($event);
  }

}
