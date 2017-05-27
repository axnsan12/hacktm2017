import {Component, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {DataService} from '../../../../services/data.service';
import {Service} from '../../../../models/service';
import {Package} from '../../../../models/package';

@Component({
  selector: 'app-results',
  templateUrl: './results.component.html',
  styleUrls: ['./results.component.scss']
})
export class ResultsComponent implements OnInit, OnChanges {

  @Input()
  public service: Service = null;
  private oldServiceId: number = -1;
  public packages: Package[];

  constructor(private dataService: DataService) {
  }

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.oldServiceId === this.service.id) {
      return;
    }
    this.oldServiceId = this.service.id;
    const subscription = this.dataService.getPackages(this.service.id).subscribe(packages => {
      this.gotResults(packages);
      subscription.unsubscribe();
    });
  }

  private gotResults(packages) {
    console.log('got packages');
    console.log(packages);
  }
}
