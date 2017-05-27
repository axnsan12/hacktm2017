import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {Service} from '../models/service';
import {Observer} from 'rxjs/Observer';
import {Characteristic} from '../models/characteristic';

@Injectable()
export class MockStaticInfoService {

  private mockServices: Service[] = [
    {
      id: 1,
      name: 'Telefonie și internet mobil',
      alias:'mobile'
    },
    {
      id: 2,
      name: 'Televiziune',
      alias: 'tv'
    },
    {
      id: 3,
      name: 'Internet fix',
      alias:'net'
    },
    {
      id: 4,
      name: 'Energie',
      alias: 'energy'
    },
    {
      id: 5,
      name: 'Gaz',
      alias: 'gas'
    }
  ];

  private mockCharacteristics: { [id: number]: Characteristic[] } = {
    1: [
      {
        id: 1,
        name: 'Minute în rețea',
        alias: 'minute-in-retea',
        range: [0, 100],
        mu: 'bucăț'
      },
      {
        id: 2,
        name: 'Minute naționale',
        alias: 'minute-naționale',
        range: [0, 100],
        mu: 'bucăț'
      },
      {
        id: 3,
        name: 'SMS naționale',
        alias: 'sms-nationale',
        range: [0, 100],
        mu: 'bucăț'
      },
      {
        id: 4,
        name: 'Internet mobil',
        alias: 'internet-mobil',
        range: [0, 100],
        mu: 'bucăț'
      }
    ],
    3: [
      {
        id: 1,
        name: 'Viteză',
        alias: 'speed',
        range: [0, 100],
        mu: 'bucăț'
      },
      {
        id: 2,
        name: 'Limită trafic',
        alias: 'traffic-limit',
        range: [0, 100],
        mu: 'bucăț'
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
