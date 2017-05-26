import { Hacktm2017FrontendPage } from './app.po';

describe('hacktm2017-frontend App', () => {
  let page: Hacktm2017FrontendPage;

  beforeEach(() => {
    page = new Hacktm2017FrontendPage();
  });

  it('should display message saying cuipasa works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('cuipasa works!');
  });
});
