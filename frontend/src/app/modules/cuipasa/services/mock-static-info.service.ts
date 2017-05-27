import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {Service} from '../models/service';
import {Observer} from 'rxjs/Observer';
import {Characteristic} from "../models/characteristic";

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

  private mockCharacteristics: { [id: number]: Characteristic[] } = {
    1: [
      {
        id: 1,
        name: 'minute-in-retea',
        alias: 'Minute în rețea'
      },
      {
        id: 2,
        name: 'minute-naționale',
        alias: 'Minute naționale'
      },
      {
        id: 2,
        name: 'sms-nationale',
        alias: 'SMS naționale'
      },
      {
        id: 3,
        name: 'internet-mobil',
        alias: 'Internet mobil'
      }
    ],
    3: [
      {
        id: 1,
        name: 'speed',
        alias: 'Viteză'
      },
      {
        id: 1,
        name: 'traffic-limit',
        alias: 'Limită trafic'
      }
    ]
  };

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

  public getServiceCharacteristics(serviceId: number): Observable<Characteristic[]> {
    let observer: Observer<Characteristic[]>;
    const observable = Observable.create(obs => {
      observer = obs;
    });
    setTimeout(() => {
      let characteristics: Characteristic[] = this.mockCharacteristics[serviceId];
      if (characteristics == null) {
        characteristics = [];
      }
      observer.next(characteristics);
    }, 150);
    return observable;
  }

}
