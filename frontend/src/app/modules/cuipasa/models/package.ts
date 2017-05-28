import {Characteristic} from './characteristic';
export interface Package {
  id: number;
  package_name: string;
  price: number;
  characteristics: Characteristic[];
}
