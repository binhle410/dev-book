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

    navBarTitle = 'Your Books on the Cloud';

    open() {
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

            console.log('/////////////////////////////', 'contextData Id is ', contextData);
            // this.selectContext$.next(contextId);
            // this.dataTransfer.setNavBarTitle(contextData);
            if (contextData != undefined && contextData != null && contextData != '') {
                this.navBarTitle = contextData;
            } else {
                this.navBarTitle = 'Your Books on the Cloud';
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
    template: `
        <div class="modal-header">
            <h5 class="modal-title text-center">Modal title</h5>
            <button type="button" class="close" aria-label="Close" (click)="activeModal.dismiss('Cross click')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body"> Far far away, behind the word mountains, far from the countries Vokalia and
            Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the
            Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the
            necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your
            mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic
            life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far
            World of Grammar.
        </div>
        <div class="modal-footer">
            <div class="left-side">
                <button type="button" class="btn btn-default btn-link" (click)="activeModal.close('Close click')">Never
                    mind
                </button>
            </div>
            <div class="divider"></div>
            <div class="right-side">
                <button type="button" class="btn btn-danger btn-link" (click)="activeModal.close('Close click')">
                    DELETE
                </button>
            </div>
        </div>
    `
})
export class BookListingNgbdModalContent {
    @Input() name;

    constructor(public activeModal: NgbActiveModal) {
    }
}