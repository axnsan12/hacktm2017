import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {Service} from '../models/service';
import {Observer} from 'rxjs/Observer';

@Injectable()
export class MockStaticInfoService {

  private mockServices: Service[] = [
    {
      id: 1,
      name: 'Telefonie și internet mobil'
    },
    {
      id: 2,
      name: 'Televiziune'
    },
    {
      id: 3,
      name: 'Internet fix'
    },
    {
      id: 4,
      name: 'Energie'
    },
    {
      id: 5,
      name: 'Gaz'
    }
  ];

  constructor() {
  }

  public getServices(): Observable<Service[]> {
    let observer: Observer<Service[]>;
    const observable = Observable.create(obs => {
      observer = obs;
    });
    setTimeout(() => {
      observer.next(this.mockServices);
    }, 150);
    return observable;
  }


}
