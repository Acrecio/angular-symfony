import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';

import { APIService } from '../api.service';
import { HttpErrorResponse } from '@angular/common/http';
import { Router } from '@angular/router';
import { TokenService } from '../token.service';

@Component({
  selector: 'app-hello',
  templateUrl: './hello.component.html',
  styleUrls: ['./hello.component.css']
})
export class HelloComponent implements OnInit {
  $hello: Observable<{ hello?: string }>;
  helloMessage: string;

  constructor(
    private apiService: APIService,
    private tokenService: TokenService,
    private router: Router
  ) {}

  ngOnInit() {
    // Example API call showing an Hello World
    this.$hello = this.apiService.getHello();

    this.$hello.subscribe(
      // Show API response
      ({ hello }) => {
        console.log(`Received from server ${hello}`);
        this.helloMessage = hello;
      },
      // Log error message and redirect to login
      (error: HttpErrorResponse) => {
        console.error(error);
        if (error.status === 401) {
          return this.router.navigate(['']);
        }
      }
    );
  }

  onGoBack() {
    return this.router.navigate(['']);
  }
}
