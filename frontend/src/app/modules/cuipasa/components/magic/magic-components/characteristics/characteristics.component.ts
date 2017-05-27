import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {Service} from '../../../../models/service';
import {Characteristic} from '../../../../models/characteristic';
import {StaticInfoService} from '../../../../services/static-info.service';
import 'rxjs/add/operator/toPromise';

@Component({
  selector: 'app-characteristics',
  templateUrl: './characteristics.component.html',
  styleUrls: ['./characteristics.component.scss']
})
export class CharacteristicsComponent implements OnInit, OnChanges {

  @Input()
  public service: Service;

  public characteristics: Characteristic[];

  constructor(private staticInfoService: StaticInfoService) {
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    // console.log(this.service);
    if (this.service === null) {
      this.characteristics = [];
      return;
    }
    const subscription = this.staticInfoService.getServiceCharacteristics(this.service.id)
      .subscribe(characteristics => {
        console.log(characteristics);
        this.characteristics = characteristics;
        subscription.unsubscribe();
      });
  }

}
