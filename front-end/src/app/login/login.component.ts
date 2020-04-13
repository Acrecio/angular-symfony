import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';
import { Router } from '@angular/router';

import { APIService } from '../api.service';
import { TokenService } from '../token.service';
import { Observable } from 'rxjs';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  $login: Observable<{ token?: string }>;
  token: string;
  error: string;

  credentialsForm = new FormGroup({
    username: new FormControl(''),
    password: new FormControl('')
  });

  constructor(
    private apiService: APIService,
    private tokenService: TokenService,
    private router: Router
  ) { }

  ngOnInit() {
    this.token = this.tokenService.getAuthorizationToken();
  }

  onSubmit() {
    const credentials: { username: string; password: string } = this
      .credentialsForm.value;

    // Login should return jwt token
    this.$login = this.apiService.postCredentials(credentials);

    this.$login.subscribe(
      // Show generated token
      ({ token }) => {
        console.log('Received JWT token', token);
        this.tokenService.setAuthorizationToken(token);
        this.token = token;
      },
      // Show server error
      (error: HttpErrorResponse) => {
        console.error(error);
        this.error = error.message;
      }
    );
  }

  onLogout() {
    this.tokenService.cleanAuthorizationToken();
    this.token = null;
    return this.router.navigate(['']);
  }
}
