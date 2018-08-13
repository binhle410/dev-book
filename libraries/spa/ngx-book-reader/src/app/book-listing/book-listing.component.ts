import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
import {ScrollSpyIndexService, ScrollSpyService} from "ngx-scrollspy";
import {MessageService} from "../model/message.service";
import {DataTransferService} from "../model/data-transfer.service";
import {NgbActiveModal, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {BookModalTocComponent} from "../book-modal-toc/book-modal-toc.component";
import { BookService } from '../model/book.service';
import { Book } from '../model/book';

@Component({
    selector: 'app-book-listing',
    templateUrl: './book-listing.component.html',
    styleUrls: ['./book-listing.component.scss']
})
export class BookListingComponent implements OnInit, AfterViewInit {
    constructor(
        private modalService: NgbModal, 
        private dataTransfer: DataTransferService, 
        private scrollSpyService: ScrollSpyService, 
        private scrollSpyIndex: ScrollSpyIndexService,
        private bookService: BookService
    ) {}

    bookHeading = 'Your Books on the Cloud';
    bookSubHeading = '';
    inSubChapter = false;
    bookList: Book[];

    openToc() {
        const modalRef = this.modalService.open(BookModalTocComponent);
        modalRef.componentInstance.name = 'World';
    }

    ngAfterViewInit() {

    }

    ngOnInit() {
        this.bookService.all().subscribe(books => {
            console.log(books);
            this.bookList = books;
        })
    }

}