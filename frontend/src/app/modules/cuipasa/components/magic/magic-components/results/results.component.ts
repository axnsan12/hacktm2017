import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {DataService} from '../../../../services/data.service';
import {Package} from '../../../../models/package';
import {MagicModel} from '../../../../models/magic-model';
import {Characteristic} from '../../../../models/characteristic';

@Component({
  selector: 'app-results',
  templateUrl: './results.component.html',
  styleUrls: ['./results.component.scss']
})
export class ResultsComponent implements OnInit, OnChanges {

  @Input()
  public magicData: MagicModel = null;
  private oldServiceId: number = -1;
  public packages: Package[] = [];

  public displayPackages: Package[] = [];

  constructor(private dataService: DataService) {
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (!this.magicData || !this.magicData.service || this.oldServiceId === this.magicData.service.id) {
      this.gotResults(this.packages);
      return;
    }
    this.oldServiceId = this.magicData.service.id;
    const subscription = this.dataService.getPackages(this.magicData.service.id).subscribe(packages => {
      this.gotResults(packages);
      subscription.unsubscribe();
    });
  }

  private gotResults(packages) {
    console.log('got packages');
    this.displayPackages = [];
    // console.log(packages);
    // this.packages = packages;
    // console.log(this.packages.length);
    //
    this.packages = packages;
    if (this.magicData === null) {
      this.displayPackages = this.packages;
      return;
    }
    this.displayPackages = [];
    // console.log(this.magicData);
    this.packages.forEach(pack => {
      let ok = true;
      //   // console.log(this.magicData.filters, this.magicData.filters.length);
      this.magicData.filters.forEach(filter => {
        const charId = filter.characteristic.id;
        //       console.log(filter.characteristic);
        const chhh = this.getCharacteristicWithId(pack.characteristics, charId);
        if (chhh == null) {
          return;
        }
        // console.log(chhh.value);
        // console.log(filter);
        var lim: any = filter.limits;
        if (lim) {
          if (chhh.value < filter.limits[0] || chhh.value > filter.limits[1]) {
            ok = false;
          }
        }
        // console.log(charId);
        // console.log(chhh);
      });
      // console.log(pack);
      if (ok) {
        this.displayPackages.push(pack);
      }
    });
  }


  private getCharacteristicWithId(characteristics: Characteristic[], id: number) {
    let toRet: Characteristic = null;
    characteristics.forEach(characteristic => {
      if (characteristic.id === id) {
        toRet = characteristic;
      }
    });
    return toRet;
  }
}
