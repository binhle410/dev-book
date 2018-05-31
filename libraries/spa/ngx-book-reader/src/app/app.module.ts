import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NavbarComponent } from './shared/navbar/navbar.component';
import { ScrollSpyModule } from 'ngx-scrollspy';
import { ScrollSpyIndexModule } from 'ngx-scrollspy';
import { ScrollSpyAffixModule } from 'ngx-scrollspy';

import { NgModule } from '@angular/core';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { AppRoutingModule } from './app-routing.module';
import {BookListingComponent} from './book-listing/book-listing.component';
import { BookReaderComponent } from './book-reader/book-reader.component';
import { BookModalTocComponent } from './book-modal-toc/book-modal-toc.component';
import { BookContactComponent } from './book-contact/book-contact.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    NavbarComponent,
    BookListingComponent,
    BookReaderComponent,
    BookModalTocComponent,
    BookContactComponent
  ],
    entryComponents: [BookModalTocComponent],
    imports: [
    BrowserModule,
    FormsModule,
    ScrollSpyModule.forRoot(),
    ScrollSpyIndexModule,
    ScrollSpyAffixModule,

    AppRoutingModule,
    NgbModule.forRoot()
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
