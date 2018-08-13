import { Component, OnInit } from '@angular/core';

import {NgbDateStruct} from '@ng-bootstrap/ng-bootstrap';
import { SecurityService } from '../model/security.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
    dob: NgbDateStruct;
    code: string;
    coded: boolean;
    nric: string;

  constructor(
    private securityService: SecurityService
  ) { }

  ngOnInit() {
    this.code = localStorage.getItem('code');
    if (this.code) {
      this.coded = true;
    } else {
      this.coded = false;
    }
  }

  login() {
    if (this.dob && this.code && this.nric) {
      let username = `${this.code}@at@${this.nric}`;
      let year = this.dob.year.toString();
      let month = this.dob.month.toString();
      if (month.length == 1) month = '0' + month;    
      let day = this.dob.day.toString();
      if (day.length == 1) day = '0' + day;
      let password = `${year}${month}${day}`;
      console.log(username, password);
      this.securityService.checkLogin(username, password).subscribe(res => {
        console.log(res);
      }) 
    }
  }
}
