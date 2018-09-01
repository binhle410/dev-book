import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class MessageService {
  navBarTitle: string  = 'Your Books on the Cloud';
  constructor() { }
}
