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
    @Input() name;

    constructor(public activeModal: NgbActiveModal) {
    }
}
