import {Component, Input, OnInit} from '@angular/core';
import {NgbActiveModal} from "@ng-bootstrap/ng-bootstrap";

@Component({
  selector: 'app-book-modal-toc',
  templateUrl: './book-modal-toc.component.html',
  styleUrls: ['./book-modal-toc.component.scss']
})
export class BookModalTocComponent implements OnInit {

  ngOnInit() {
  }
    @Input() book;
    @Input() chapters;

    constructor(public activeModal: NgbActiveModal) {
    }

    scrollToChapter(id: number, up: number) {
      document.querySelector(`#chapter-${id}`).scrollIntoView();
      window.scrollTo(window.scrollX, window.scrollY - up);
      this.activeModal.close('Scroll to chapter');
    }
}
