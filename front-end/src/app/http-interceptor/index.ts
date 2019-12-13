import { HTTP_INTERCEPTORS } from '@angular/common/http'

import { WSSEInterceptor } from './wsse-interceptor'

/** Http interceptor providers in outside-in order */
export const httpInterceptorProviders = [
  { provide: HTTP_INTERCEPTORS, useClass: WSSEInterceptor, multi: true }
]
