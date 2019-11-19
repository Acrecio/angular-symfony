import { Injectable } from '@angular/core';

import { HttpClient } from '@angular/common/http';

import { environment } from '../environments/environment';
import { Observable } from 'rxjs';

interface credentialsType {
  username: string;
  password: string;
}

@Injectable({
  providedIn: 'root'
})
export class WSSEService {

  constructor(
    private httpClient: HttpClient
  ) { }

  postCredentials(credentials: credentialsType) : Observable<{secret?: string}> {
    return this.httpClient.post(environment.server + '/login', credentials);
  }

  getHello() : Observable<{hello?: string}> {
    return this.httpClient.get(environment.server + '/api/hello');
  }
}
