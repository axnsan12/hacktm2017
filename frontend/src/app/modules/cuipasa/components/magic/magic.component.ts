import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {StaticInfoService} from '../../services/static-info.service';
import {Service} from '../../models/service';
import {Filter} from "../../models/filter";

@Component({
  selector: 'app-magic',
  templateUrl: './magic.component.html',
  styleUrls: ['./magic.component.scss']
})
export class MagicComponent implements OnInit {

  public service: Service = null;
  public filters: Filter[] = [];

  @Output()
  public done: EventEmitter<any> = new EventEmitter();

  constructor(private staticInfoService: StaticInfoService) {
  }

  ngOnInit() {
  }

  public selectedServiceChanged(service) {
    // this.staticInfoService.getServiceCharacteristics(service.id).subscribe(data => {
    //   console.log(data);
    // });
    this.service = service;
    this.triggerDoneEvent();
  }

  public filtersUpdated($event) {
    this.filters = $event;
    this.triggerDoneEvent();
    // console.log("filters updated");
    // console.log($event);
  }

  public triggerDoneEvent() {
    this.done.next({service: this.service, filters: this.filters});
  }
}
