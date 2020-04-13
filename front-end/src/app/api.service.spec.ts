import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';

import { APIService } from './api.service';

describe('APIService', () => {
  beforeEach(() =>
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule]
    })
  );

  it('should be created', () => {
    const service: APIService = TestBed.get(APIService);
    expect(service).toBeTruthy();
  });
});
