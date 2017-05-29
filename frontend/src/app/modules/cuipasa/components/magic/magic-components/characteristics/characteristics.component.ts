import {Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges} from '@angular/core';
import {Service} from '../../../../models/service';
import {Characteristic} from '../../../../models/characteristic';
import {StaticInfoService} from '../../../../services/static-info.service';
import 'rxjs/add/operator/toPromise';
import {Filter} from '../../../../models/filter';
import {DataService} from '../../../../services/data.service';

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

  public priceRange: number[];


  constructor(private staticInfoService: StaticInfoService, private dataService: DataService) {
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    // console.log(this.service);
    if (!this.service) {
      this.characteristics = [];
      return;
    }


    const subs3 = this.dataService.getPackages(this.service.id).subscribe(packages => {

      let min = 0;
      let max = 9999999;
      packages.forEach(pack => {
        // console.log(pack.price);
        min = Math.min(pack.price, min);
        max = Math.max(pack.price, max);
      });
      this.priceRange = [min, max];
      this.buildPriceSlider();

      const subs2 = this.staticInfoService.getServicheCharacteristicsMinMax(this.service.id)
        .subscribe(minMaxs => {
          this.minMaxs = minMaxs;
          console.log(minMaxs);
          subs2.unsubscribe();
          const subs1 = this.staticInfoService.getServiceCharacteristics(this.service.id)
            .subscribe(characteristics => {
              // console.log(characteristics);
              this.characteristics = characteristics;
              characteristics.forEach(chrr => {
                const mm = this.minMaxs.find(mm1 => mm1.sc_id === chrr.id);
                console.log(mm);
                chrr.range = [parseFloat(mm.minValue), parseFloat(mm.maxValue)];
              });
              subs1.unsubscribe();
            });
        });
      subs3.unsubscribe();
    });
  }

  public toggleCharacteristic(characteristic: Characteristic): void {
    characteristic.selected = !characteristic.selected;
    if (characteristic.selected) {
      setTimeout(() => {
        const className = 'slider-' + characteristic.alias;

        // console.log(className);

        const sliderElement: any = document.getElementsByClassName(className)[0];

        const range = characteristic.range || [0, 1000];
        if (range[0] === range[1]) {
          range[1]++;
        }
        const step = Math.round((characteristic.range[1] - characteristic.range[0]) / 100);
        const sliderOptions = {
          start: range,
          connect: true,
          step: step,
          range: {
            'min': characteristic.range ? characteristic.range[0] : 0,
            'max': characteristic.range ? characteristic.range[1] : 1000
          }
        };
        // console.log(sliderOptions);
        noUiSlider.create(sliderElement, sliderOptions);

        this.filtersUpdated();
      }, 250);
    } else {
      this.filtersUpdated();
    }
  }

  private buildPriceSlider() {

    const sliderElement: any = document.getElementsByClassName('slider-price-range')[0];
    const sliderOptions = {
      start: this.priceRange,
      connect: true,
      step: (this.priceRange[1] - this.priceRange[0]) / 100,
      range: {
        'min': this.priceRange[0],
        'max': this.priceRange[1]
      }
    };
    noUiSlider.create(sliderElement, sliderOptions);
    sliderElement.noUiSlider.on('change', () => {
      console.log('price slider changed');
    });
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
