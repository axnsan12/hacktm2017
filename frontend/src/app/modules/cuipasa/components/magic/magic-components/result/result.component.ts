import { Component, OnInit } from '@angular/core';
import {Characteristic} from "../../../../models/characteristic";
import {StaticInfoService} from "../../../../services/static-info.service";

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {

  private characteristics :Characteristic[] = [];

  constructor(private staticInfoService: StaticInfoService) {

    //am luat caracteristicile de la serviciul nr 1
    this.staticInfoService.getServiceCharacteristics(1).subscribe(characteristics => {
      this.characteristics = characteristics;
    });


  }




  ngOnInit() {
  }

}
