import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {Service} from '../models/service';
import {MockStaticInfoService} from './mock-static-info.service';
import {Characteristic} from '../models/characteristic';

@Injectable()
export class StaticInfoService {

  constructor(private mockStaticService: MockStaticInfoService) {
  }

  public getServices(): Observable<Service[]> {
    return this.mockStaticService.getServices();
  }

  public getServiceCharacteristics(serviceId: number): Observable<Characteristic[]> {
    return this.mockStaticService.getServiceCharacteristics(serviceId);
  }

}
