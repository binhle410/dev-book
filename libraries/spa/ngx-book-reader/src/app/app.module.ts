import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NavbarComponent } from './shared/navbar/navbar.component';
import { ScrollSpyModule } from 'ngx-scrollspy';
import { ScrollSpyIndexModule } from 'ngx-scrollspy';
import { ScrollSpyAffixModule } from 'ngx-scrollspy';

import { NgModule, NO_ERRORS_SCHEMA } from '@angular/core';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { EditorModule } from "@tinymce/tinymce-angular";

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { AppRoutingModule } from './app-routing.module';
import { BookListingComponent } from './book-listing/book-listing.component';
import { BookReaderComponent } from './book-reader/book-reader.component';
import { BookModalTocComponent } from './book-modal-toc/book-modal-toc.component';
import { BookContactComponent } from './book-contact/book-contact.component';
import { BookChapterComponent } from './book-chapter/book-chapter.component';
import { HttpClientModule } from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    NavbarComponent,
    BookListingComponent,
    BookReaderComponent,
    BookModalTocComponent,
    BookContactComponent,
    BookChapterComponent
  ],
  entryComponents: [BookModalTocComponent],
  imports: [
    BrowserModule,
    FormsModule,
    ScrollSpyModule.forRoot(),
    ScrollSpyIndexModule,
    ScrollSpyAffixModule,
    HttpClientModule,
    AppRoutingModule,
    NgbModule.forRoot(),
    EditorModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
