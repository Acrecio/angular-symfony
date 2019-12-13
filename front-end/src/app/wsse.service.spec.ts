import { TestBed } from '@angular/core/testing'

import { WSSEService } from './wsse.service'
import { HttpClientTestingModule } from '@angular/common/http/testing'

describe('WSSEService', () => {
  beforeEach(() =>
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule]
    })
  )

  it('should be created', () => {
    const service: WSSEService = TestBed.get(WSSEService)
    expect(service).toBeTruthy()
  })
})
