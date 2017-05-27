import {Component, OnInit} from '@angular/core';
import {StaticInfoService} from '../../services/static-info.service';
import {Service} from '../../models/service';

@Component({
  selector: 'app-magic',
  templateUrl: './magic.component.html',
  styleUrls: ['./magic.component.scss']
})
export class MagicComponent implements OnInit {

  public service: Service = null;

  constructor(private staticInfoService: StaticInfoService) {
  }

  ngOnInit() {
  }

  public selectedServiceChanged(service) {
    // this.staticInfoService.getServiceCharacteristics(service.id).subscribe(data => {
    //   console.log(data);
    // });
    this.service = service;
  }
}
