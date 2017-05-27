import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {Service} from '../../../../models/service';
import {Characteristic} from '../../../../models/characteristic';
import {StaticInfoService} from '../../../../services/static-info.service';
import 'rxjs/add/operator/toPromise';

declare var noUiSlider: any;

@Component({
  selector: 'app-characteristics',
  templateUrl: './characteristics.component.html',
  styleUrls: ['./characteristics.component.scss']
})
export class CharacteristicsComponent implements OnInit, OnChanges {

  @Input()
  public service: Service;

  public characteristics: Characteristic[];

  public someRange = 2;

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

  public toggleCharacteristic(characteristic): void {
    characteristic.selected = !characteristic.selected;
    if (characteristic.selected) {
      setTimeout(() => {
        const className = 'slider-' + characteristic.id;
        const sliderElement: any = document.getElementsByClassName(className)[0];

        const sliderOptions = {
          start: characteristic.range,
          connect: true,
          step: 10,
          range: {
            'min': characteristic.range[0],
            'max': characteristic.range[1]
          }
        };
        console.log(sliderOptions);
        noUiSlider.create(sliderElement, sliderOptions);
        sliderElement.noUiSlider.on('change', function () {
          const limits = sliderElement.noUiSlider.get();
          characteristic.values = [
            Math.round(limits[0]),
            Math.round(limits[1])
          ];
        });
      }, 50);
    }
  }

}
