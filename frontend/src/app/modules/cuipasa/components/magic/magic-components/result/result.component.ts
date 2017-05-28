import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {Package} from '../../../../models/package';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit, OnChanges {

  @Input()
  public pack: Package;

  constructor() {

  }


  ngOnChanges(changes: SimpleChanges): void {
    // if (this.pack && this.pack.characteristics && this.pack.characteristics.length) {
    //   const pck: any = this.pack;
    //   pck.company_name = this.pack.characteristics[0].company_name;
    // }
    console.log(this.pack);
  }

  ngOnInit() {
  }

}
