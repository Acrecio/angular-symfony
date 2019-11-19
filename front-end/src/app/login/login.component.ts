import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';
import { Router } from '@angular/router';

import { WSSEService } from '../wsse.service';
import { TokenService } from '../token.service';
import { Observable } from 'rxjs';
import { flatMap } from 'rxjs/operators';
import { HttpErrorResponse } from '@angular/common/http';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  $login: Observable<{ secret?: string }>;
  username: string;
  secret: string;
  created: string;
  error: string;

  credentialsForm = new FormGroup({
    username: new FormControl(''),
    password: new FormControl(''),
  });

  constructor(
    private wsseService: WSSEService,
    private tokenService: TokenService,
    private router: Router,
  ) { }

  ngOnInit() {
    if (this.tokenService.hasAuthorizationToken()) {
      this.username = localStorage.getItem('WSSE-Username');
      this.created = localStorage.getItem('WSSE-CreatedAt');
      this.secret = localStorage.getItem('WSSE-Secret');
    }
  }

  onSubmit() {
    let credentials: { username: string, password: string } = this.credentialsForm.value;

    // Login should return user secret (hashed password)
    this.$login = this.wsseService.postCredentials(credentials);

    this.$login
      .subscribe(
        // Show generated token
        ({ secret }) => {
          this.username = credentials.username;
          this.created = this.tokenService.formatDate(new Date());
          this.secret = secret;

          let authToken = this.tokenService.generateWSSEToken(this.username, this.created, this.secret);

          console.log(`Generated WSSE Token ${authToken}`);
        },
        // Show server error
        (error: HttpErrorResponse) => {
          console.error(error);
          this.error = error.message;
        });
  }

  onLogout() {
    this.tokenService.cleanAuthorizationToken();
    this.secret = null;
    return this.router.navigate(['']);
  }
}
