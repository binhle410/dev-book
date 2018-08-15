import {AfterViewInit, Component, ElementRef, Input, OnInit} from '@angular/core';
import {ScrollSpyIndexService, ScrollSpyService} from "ngx-scrollspy";
import {MessageService} from "../model/message.service";
import {DataTransferService} from "../model/data-transfer.service";
import {NgbActiveModal, NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {BookModalTocComponent} from "../book-modal-toc/book-modal-toc.component";
import { BookChapterService } from '../model/book-chapter.service';
import { BookChapter } from '../model/book-chapter';
import { ActivatedRoute } from '@angular/router';
import { Book } from '../model/book';
import { BookService } from '../model/book.service';

@Component({
    selector: 'app-book-reader',
    templateUrl: './book-reader.component.html',
    styleUrls: ['./book-reader.component.scss']
})

export class BookReaderComponent implements OnInit, AfterViewInit {
    bookId: number;
    book: Book;
    chapters: BookChapter[];
    editing: number;
    beforeEdit: string;

    constructor(
        private bookService: BookService,
        private bookChapterService: BookChapterService, 
        private hostElement: ElementRef, 
        private modalService: NgbModal, 
        private dataTransfer: DataTransferService, 
        private scrollSpyService: ScrollSpyService, 
        private scrollSpyIndex: ScrollSpyIndexService,
        private activeRoute: ActivatedRoute
    ) { }
test1 = 1;
    scroll(el) {
        // console.log('after scroll',el.scrollTop);
        let parent = el.parentNode;
        console.log(this.hostElement, el);
        // console.log('before scroll', el.offsetTop);
        this.scrollToChapterId = parseInt(el.getAttribute('data-id'));
        const intersectionObserver = new IntersectionObserver((entries) => {
            let [entry] = entries;
            if (entry.isIntersecting) {
                setTimeout(() => {
                    // alert(`${entry.target.id} is visible!!!`);
                    // console.log('after top', el.offsetTop);
                }, 500)
            }
        });
// start observing
        intersectionObserver.observe(el);

        el.scrollIntoView({behavior: "smooth", block: "start"});

        // window.scrollBy(0, -70);
    }

    isSubChapter(id){
        return false;
    }
        ;


    isScrolledToView(id: number) {
        // console.log('this.scrollToChapterId is ',this.scrollToChapterId,'id param is',id);
        this.viewingChapter = id;
        return this.scrollToChapterId === id;
    }

    scrollToChapterId = 0;
    bookHeading = 'Your Books on the Cloud';
    bookSubHeading = '';
    inSubChapter = false;
    viewingChapter: number;

    openToc() {
        const modalRef = this.modalService.open(BookModalTocComponent);
        modalRef.componentInstance.book = this.book;
        modalRef.componentInstance.chapters = this.chapters;
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

            // console.log('/////////////////////////////', 'subChapters contextData Id is ', contextData);
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

    loadBookInfo() {
        this.activeRoute.params.subscribe(params => {
            this.bookId = params['id'];
            this.bookService.get(this.bookId).subscribe(book => this.book = book);
            this.loadChapters();
        });

    }

    loadChapters() {
        this.bookChapterService.getBookChapters(this.bookId).subscribe(chapters => {
            this.chapters = chapters;
        });
    }

    getViewingSubChapters() : BookChapter[] {
        return this.chapters.find(chapter => chapter.name == this.bookHeading).children;
    }

    scrollToChapter(id: number, up: number) {
        document.querySelector(`#chapter-${id}`).scrollIntoView();
        window.scrollTo(window.scrollX, window.scrollY - up);
    }

    ngOnInit() {
        this.loadBookInfo();
    }

    edit(chapter: BookChapter) {
        this.editing = chapter.id;
        this.beforeEdit = chapter.name;
    }

    save(chapter: BookChapter) {
        this.editing = -1;
        // fetch some api
    }

    cancel(chapter: BookChapter) {
        this.editing = -1;
        chapter.name = this.beforeEdit;
    }
}