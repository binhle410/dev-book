import { Injectable } from '@angular/core';
import { BookChapter } from "../model/book-chapter";
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute } from '@angular/router';
import { Observable, of } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookChapterService {
  
  constructor(
    private http: HttpClient
  ) { }

  getBookChapters(bookId: number) : Observable<BookChapter[]> {
    return this.getRootChapters(bookId)
    .pipe(
      map(chapters => {
        chapters.forEach(chapter => {
          this.getSubChapters(chapter.id).subscribe(subChapters => {
            chapter.children = subChapters;
          })
        })
        return chapters;
      })
    );
  }

  getRootChapters(bookId: number) : Observable<BookChapter[]> {
    return this.http.get<any>(`${environment.apiEndPoint}/books/${bookId}/root-chapters`)
    .pipe(
      map(response => response['hydra:member']),
      catchError(this.handleError('getRootChapters', []))
    );
  }

  getSubChapters(chapterId: number) : Observable<BookChapter[]> {
    return this.http.get<any>(`${environment.apiEndPoint}/chapters/${chapterId}/sub-chapters`)
    .pipe(
      map(response => response['hydra:member'] as BookChapter[]),
      catchError(this.handleError('getSubChapters', []))
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
