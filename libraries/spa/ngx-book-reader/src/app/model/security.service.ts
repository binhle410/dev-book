import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SecurityService {
  readonly loginUrl = '/login_check_org_code_nric';
  readonly headers = new HttpHeaders({
    // 'Content-Type': 'multipart/form-data'
  })

  constructor(
    private http: HttpClient
  ) { }

  checkLogin(username, password): Observable<any> {
    return this.http.post(`${environment.apiEndPoint}${this.loginUrl}`, {
      _username: username,
      _password: password
    }, {
      headers: this.headers
    }).pipe(
      
    );
  }
}
