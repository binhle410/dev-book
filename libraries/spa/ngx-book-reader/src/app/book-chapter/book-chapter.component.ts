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
  editing: number;
  beforeEdit: string;
  
  constructor() { }

  ngOnInit() {
  }

  isScrolledToView() {
    // console.log('this.scrollToChapterId is ',this.scrollToChapterId,'id param is', this.chapter.id);
    return this.scrollToChapterId === this.chapter.id;
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
