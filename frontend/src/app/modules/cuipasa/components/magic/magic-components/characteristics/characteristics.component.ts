import {Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges} from '@angular/core';
import {Service} from '../../../../models/service';
import {Characteristic} from '../../../../models/characteristic';
import {StaticInfoService} from '../../../../services/static-info.service';
import 'rxjs/add/operator/toPromise';
import {Filter} from '../../../../models/filter';

declare var noUiSlider: any;

@Component({
  selector: 'app-characteristics',
  templateUrl: './characteristics.component.html',
  styleUrls: ['./characteristics.component.scss']
})
export class CharacteristicsComponent implements OnInit, OnChanges {

  @Input()
  public service: Service;

  @Output()
  public done: EventEmitter<Filter[]> = new EventEmitter();

  public characteristics: Characteristic[];

  public minMaxs: any = [];

  public someRange = 2;

  constructor(private staticInfoService: StaticInfoService) {
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    // console.log(this.service);
    if (!this.service) {
      this.characteristics = [];
      return;
    }

    const subs2 = this.staticInfoService.getServicheCharacteristicsMinMax(this.service.id)
      .subscribe(minMaxs => {
        this.minMaxs = minMaxs;
        subs2.unsubscribe();
        const subs1 = this.staticInfoService.getServiceCharacteristics(this.service.id)
          .subscribe(characteristics => {
            // console.log(characteristics);
            this.characteristics = characteristics;
            characteristics.forEach(chrr => chrr.values = [0, 1000]);
            subs1.unsubscribe();
          });
      });
  }

  public toggleCharacteristic(characteristic: Characteristic): void {
    characteristic.selected = !characteristic.selected;
    if (characteristic.selected) {
      setTimeout(() => {
        const className = 'slider-' + characteristic.alias;

        // console.log(className);

        const sliderElement: any = document.getElementsByClassName(className)[0];

        const sliderOptions = {
          start: characteristic.range || [0, 1000],
          connect: true,
          step: 10,
          range: {
            'min': characteristic.range ? characteristic.range[0] : 0,
            'max': characteristic.range ? characteristic.range[1] : 1000
          }
        };
        // console.log(sliderOptions);
        noUiSlider.create(sliderElement, sliderOptions);
        sliderElement.noUiSlider.on('change', () => {
          const limits = sliderElement.noUiSlider.get();
          characteristic.values = [
            Math.round(limits[0]),
            Math.round(limits[1])
          ];
          this.filtersUpdated();
        });
        this.filtersUpdated();
      }, 250);
    } else {
      this.filtersUpdated();
    }
  }

  public filtersUpdated(): void {
    // console.log('filtersUpdated');
    const filters: Filter[] = [];
    this.characteristics.forEach(characteristic => {
      if (!characteristic.selected) {
        return;
      }
      filters.push({characteristic: characteristic, limits: characteristic.values});
    });
    this.done.next(filters);
  }

}
