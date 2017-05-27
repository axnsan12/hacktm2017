import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {Service} from '../../../../models/service';
import {StaticInfoService} from '../../../../services/static-info.service';

@Component({
  selector: 'app-service-selector',
  templateUrl: './service-selector.component.html',
  styleUrls: ['./service-selector.component.scss']
})
export class ServiceSelectorComponent implements OnInit {

  @Output()
  public selectedServiceChanged: EventEmitter<Service> = new EventEmitter();
  public services: Service[] = [];
  public selectedService: Service = null;

  constructor(private staticInfoService: StaticInfoService) {
    this.staticInfoService.getServices().subscribe(services => {
      this.gotServices(services);
    });
  }

  ngOnInit() {
  }

  private gotServices(services: Service[]) {
    this.services = services;
  }

  public serviceClicked(service: Service) {
    if (this.isServiceSelected(service)) {
      return;
    }
    this.selectedService = service;
    this.selectedServiceChanged.next(this.selectedService);
  }

  public isServiceSelected(service: Service): boolean {
    return this.selectedService !== null && service.id === this.selectedService.id;
  }
}
