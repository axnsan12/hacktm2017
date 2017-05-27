import {Component, OnInit} from '@angular/core';
import {StaticInfoService} from '../../services/static-info.service';
import {Service} from '../../models/service';

@Component({
  selector: 'app-magic',
  templateUrl: './magic.component.html',
  styleUrls: ['./magic.component.scss']
})
export class MagicComponent implements OnInit {

  public services: Service[] = [];

  constructor(private staticInfoService: StaticInfoService) {
    this.staticInfoService.getServices().subscribe(services => {
      this.gotServices(services);
    });
  }

  ngOnInit() {
  }

  private gotServices(services: Service[]) {
    console.log(services);
    this.services = services;
  }

}
