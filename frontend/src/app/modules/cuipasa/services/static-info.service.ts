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
    return this.mockStaticService.getServiceCharacteristics(serviceId);
  }

}
