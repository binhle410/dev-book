import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
import {ScrollSpyIndexService, ScrollSpyService} from "ngx-scrollspy";
import {MessageService} from "../model/message.service";
import {DataTransferService} from "../model/data-transfer.service";
import {NgbActiveModal, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {BookModalTocComponent} from "../book-modal-toc/book-modal-toc.component";

@Component({
    selector: 'app-book-reader',
    templateUrl: './book-reader.component.html',
    styleUrls: ['./book-reader.component.scss']
})

export class BookReaderComponent implements OnInit, AfterViewInit {
    constructor(private modalService: NgbModal, private dataTransfer: DataTransferService, private scrollSpyService: ScrollSpyService, private scrollSpyIndex: ScrollSpyIndexService) {
    }

    bookHeading = 'Your Books on the Cloud';
    bookSubHeading = '';
    inSubChapter = false;

    openToc() {
        const modalRef = this.modalService.open(BookModalTocComponent);
        modalRef.componentInstance.name = 'World';
    }

    ngAfterViewInit() {
        this.scrollSpyService.getObservable('window').subscribe((e: any) => {
            // console.log('e.target.pageYOffset: ', e.target.pageYOffset, 'e.target.scrollY: ', e.target.scrollY, 'e.target.scrollingElement.scrollTop: ', e.target.scrollingElement.scrollTop);
            let currentScrollPosition: number;
            if (typeof e.target.scrollingElement !== 'undefined') {
                currentScrollPosition = e.target.scrollingElement.scrollTop;
            } else if (typeof e.target.scrollY !== 'undefined') {
                currentScrollPosition = e.target.scrollY;
            } else if (typeof e.target.pageYOffset !== 'undefined') {
                currentScrollPosition = e.target.pageYOffset;
            }

            let items: any[] = this.scrollSpyIndex.getIndex('chapters');

            if (!items || !items.length) {
                return;
            }

            let contextData: string;
            for (let i = items.length - 1; i >= 0; i--) {
                if (currentScrollPosition - items[i].offsetTop >= 0) {
                    // console.log('items[i]',items[i]);
                    contextData = items[i].getAttribute('data-content');
                    break;
                }
            }

            // console.log('/////////////////////////////', 'contextData Id is ', contextData);
            // this.selectContext$.next(contextId);
            // this.dataTransfer.setbookHeading(contextData);
            if (contextData != undefined && contextData != null && contextData != '') {
                this.bookHeading = contextData;
            } else {
                this.bookHeading = 'Your Books on the Cloud';
            }

            let subChapters: any[] = this.scrollSpyIndex.getIndex('subChapters');
            // console.log('subChapters', subChapters);

            if (!subChapters || !subChapters.length) {
                return;
            }

            contextData = '';
            for (let i = subChapters.length - 1; i >= 0; i--) {
                if (currentScrollPosition - subChapters[i].offsetTop >= 0) {
                    // console.log('items[i]',items[i]);
                    contextData = subChapters[i].getAttribute('data-content');
                    break;
                }
            }

            console.log('/////////////////////////////', 'subChapters contextData Id is ', contextData);
            // this.selectContext$.next(contextId);
            // this.dataTransfer.setbookHeading(contextData);
            if (contextData != undefined && contextData != null && contextData != '') {
                this.bookSubHeading = contextData;
                this.inSubChapter = true;
            } else {
                this.inSubChapter = false;
                this.bookSubHeading = '';
            }

        });
        // let items: any[] = this.scrollSpyIndex.getIndex('contexts');
        // console.log('items í ', items);

    }

    ngOnInit() {

    }

}