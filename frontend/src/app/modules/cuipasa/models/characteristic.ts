export interface Characteristic {
  id: number;
  name: string;
  alias: string;
  selected?: boolean;
  range?: any[];
  mu: string;
  values?: number[];
  type?: string;
  value?: string;
  company_name?: string;
}
