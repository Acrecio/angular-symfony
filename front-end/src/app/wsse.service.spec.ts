import { TestBed } from '@angular/core/testing';

import { WSSEService } from './wsse.service';

describe('WSSEService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: WSSEService = TestBed.get(WSSEService);
    expect(service).toBeTruthy();
  });
});
