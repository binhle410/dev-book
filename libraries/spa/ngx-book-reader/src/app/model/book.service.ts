import { Injectable } from '@angular/core';
import { Book } from './book';
import { HttpClient } from '@angular/common/http';
import { Observable, of, ObservableInput } from 'rxjs';
import { map, tap, catchError } from 'rxjs/operators';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookService {
  constructor(
    private http: HttpClient
  ) { }

  all() : Observable<Book[]> {
    return this.http.get<any>(`${environment.apiEndPoint}/org-books`)
    .pipe(
      map(response => response['hydra:member'] as Book[]),
      catchError(this.handleError('get all book', []))
    );
  }

  get(id: number) {
    return this.http.get<any>(`${environment.apiEndPoint}/org-books/${id}`)
    .pipe(
      catchError(this.handleError('get a book', []))
    );
  }

  private handleError<T> (operation = 'operation', result?: T) {
    return (error: any): Observable<T> => {
      console.error(error);
      console.log(`${operation} failed: ${error.message}`);
      return of(result as T);
    }
  }
}
