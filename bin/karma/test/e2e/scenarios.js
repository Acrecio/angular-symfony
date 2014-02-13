'use strict';

/* http://docs.angularjs.org/guide/dev_guide.e2e-testing */

describe('my app', function() {

	var ptor;

  beforeEach(function() {
  	ptor = protractor.getInstance();
    ptor.ignoreSynchronization = true;
    browser.get('app_dev.php/');
  });


  it('should automatically redirect to /view1 when location hash/fragment is empty', function() {
    expect(browser.getCurrentUrl()).toMatch("/#/view1");
  });

  describe('view1', function() {

    beforeEach(function() {
    	ptor = protractor.getInstance();
			ptor.ignoreSynchronization = true;
      browser.get('#/view1');
    });


    it('should render view1 when user navigates to /view1', function() {
      expect(element.all(by.css('[ng-view] p')).first().getText()).
        toMatch(/partial for view 1/);
    });

  });


  describe('view2', function() {

    beforeEach(function() {
    	ptor = protractor.getInstance();
			ptor.ignoreSynchronization = true;
      browser.get('#/view2');
    });


    it('should render view2 when user navigates to /view2', function() {
      expect(element.all(by.css('[ng-view] p')).first().getText()).
        toMatch(/partial for view 2/);
    });

  });
});
