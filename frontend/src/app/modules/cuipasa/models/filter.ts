import {Characteristic} from './characteristic';
export interface Filter {
  characteristic: Characteristic;
  limits: any[];
}
