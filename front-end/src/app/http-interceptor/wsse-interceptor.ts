import { TokenService } from '../token.service'
import { Injectable } from '@angular/core'
import { HttpInterceptor, HttpRequest, HttpHandler } from '@angular/common/http'

@Injectable()
export class WSSEInterceptor implements HttpInterceptor {
  constructor (private tokenService: TokenService) {}

  intercept (req: HttpRequest<any>, next: HttpHandler) {
    let authToken = this.tokenService.getAuthorizationToken()

    if (authToken) {
      // Clone the request and replace the original headers with
      // cloned headers, updated with the authorization.
      const authReq = req.clone({
        headers: req.headers.set('X-WSSE', authToken)
      })
      // send cloned request with header to the next handler.
      return next.handle(authReq)
    } else {
      // otherwise send request without token
      return next.handle(req)
    }
  }
}
