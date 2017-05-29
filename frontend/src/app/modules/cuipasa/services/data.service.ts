import {Injectable} from '@angular/core';
import {Http} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import {Service} from '../models/service';
import {Package} from "../models/package";

@Injectable()
export class DataService {

  private backendUri = 'http://api.hacktm.tdrs.me';

  constructor(private http: Http) {
  }

  public getPackages(serviceId: number): Observable<Package[]> {
    const url = `${this.backendUri}/get/packages?service_id=${serviceId}`;
    return this.http.get(url).map(response => response.json());
  }

}
