import { Component, OnInit } from '@angular/core';

import {NgbDateStruct} from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
    dob: NgbDateStruct;
    date: {year: number, month: number};

  constructor() { }

  ngOnInit() {
  }

}
