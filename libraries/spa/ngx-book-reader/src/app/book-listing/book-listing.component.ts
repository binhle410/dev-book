import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
import {ScrollSpyIndexService, ScrollSpyService} from "ngx-scrollspy";
import {MessageService} from "../model/message.service";
import {DataTransferService} from "../model/data-transfer.service";
import {NgbActiveModal, NgbModal} from "@ng-bootstrap/ng-bootstrap";

@Component({
    selector: 'app-book-listing',
    templateUrl: './book-listing.component.html',
    styleUrls: ['./book-listing.component.scss']
})
export class BookListingComponent implements OnInit, AfterViewInit {
    constructor(private modalService: NgbModal, private dataTransfer: DataTransferService, private scrollSpyService: ScrollSpyService, private scrollSpyIndex: ScrollSpyIndexService) {
    }

    bookHeading = 'Your Books on the Cloud';
    bookSubHeading = '';
    inSubChapter = false;

    openToc() {
        const modalRef = this.modalService.open(BookListingNgbdModalContent);
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

@Component({
    selector: 'app-modal-content',
    styleUrls: ['book-modal.component.scss'],
    template: `
        <div class="modal-header">
            <h5 class="modal-title text-center">Book 001</h5>
            <button type="button" class="close" aria-label="Close" (click)="activeModal.dismiss('Cross click')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <ul>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>
                <li>Chapter 1</li>
                <li>Chapter 2
                    <ul>
                        <li>Sub Chapter 2.1</li>
                    </ul>
                </li>
                <li>Chapter 3
                    <ul>
                        <li>Sub Chapter 3.1</li>
                        <li>Sub Chapter 3.2</li>
                        <li>Sub Chapter 3.3</li>
                        <li>Sub Chapter 3.4</li>
                        <li>Sub Chapter 3.5</li>
                        <li>Sub Chapter 3.6</li>
                        <li>Sub Chapter 3.7</li>
                        <li>Sub Chapter 3.8</li>
                        <li>Sub Chapter 3.9</li>
                    </ul>
                </li>                
            </ul>
        </div>
        <div class="modal-footer">
            <div class="left-side">
                <button type="button" class="btn btn-default btn-link" (click)="activeModal.close('Close click')">CLOSE</button>
            </div>
            
            <!--<div class="divider"></div>-->
            <!--<div class="right-side">-->
                <!--<button type="button" class="btn btn-danger btn-link" (click)="activeModal.close('Close click')">-->
                    <!--DELETE-->
                <!--</button>-->
            <!--</div>-->
        </div>
    `
})
export class BookListingNgbdModalContent {
    @Input() name;

    constructor(public activeModal: NgbActiveModal) {
    }
}