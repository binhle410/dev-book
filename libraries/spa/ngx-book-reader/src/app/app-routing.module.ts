import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {LoginComponent} from "./login/login.component";
import {BookListingComponent} from "./book-listing/book-listing.component";
import {BookReaderComponent} from "./book-reader/book-reader.component";

const routes: Routes = [
    { path: 'login', component: LoginComponent },
    { path: 'books', component: BookListingComponent },
    { path: 'book-reader', component: BookReaderComponent }
];

@NgModule({
  exports: [ RouterModule ],
  imports: [
      RouterModule.forRoot(routes)
  ]
})
export class AppRoutingModule { }
