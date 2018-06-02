import { Component, OnInit } from '@angular/core';
import { Input } from '@angular/core';
import { BookChapter } from "../model/book-chapter";

@Component({
  selector: 'app-book-chapter',
  templateUrl: './book-chapter.component.html',
  styleUrls: ['./book-chapter.component.scss']
})
export class BookChapterComponent implements OnInit {
  @Input() chapter: BookChapter;
  @Input() scrollToChapterId: number;
  
  constructor() { }

  ngOnInit() {
  }

  isScrolledToView() {
    console.log('this.scrollToChapterId is ',this.scrollToChapterId,'id param is', this.chapter.id);
    return this.scrollToChapterId === this.chapter.id;
  }
}
