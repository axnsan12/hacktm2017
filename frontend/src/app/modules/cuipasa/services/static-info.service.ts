import {Injectable} from '@angular/core';
import {Service} from '../models/service';
import {MockStaticInfoService} from './mock-static-info.service';
import {Characteristic} from '../models/characteristic';
import {Http} from '@angular/http';
import 'rxjs/add/operator/map';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class StaticInfoService {

  private backendUri = 'http://api.hacktm.tdrs.me';

  constructor(private mockStaticService: MockStaticInfoService,
              private http: Http) {
  }

  public getServices(): any {
    const uri = `${this.backendUri}/get/services`;
    return this.http.get(uri).map((resp: any) => {
        return resp.json();
      }
    );
    // return this.mockStaticService.getServices();
  }

  public getServiceCharacteristics(serviceId: number): Observable<Characteristic[]> {
    // http://api.hacktm.tdrs.me/get/service/characteristics?service_id=1
    const uri = `${this.backendUri}/get/service/characteristics?service_id=${serviceId}`;
    return this.http.get(uri).map(resp => resp.json()[0].characteristics);
    // return this.mockStaticService.getServiceCharacteristics(serviceId);
  }

}
