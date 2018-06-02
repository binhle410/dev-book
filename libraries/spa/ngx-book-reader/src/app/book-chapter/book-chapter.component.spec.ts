import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BookChapterComponent } from './book-chapter.component';

describe('BookChapterComponent', () => {
  let component: BookChapterComponent;
  let fixture: ComponentFixture<BookChapterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BookChapterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BookChapterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
