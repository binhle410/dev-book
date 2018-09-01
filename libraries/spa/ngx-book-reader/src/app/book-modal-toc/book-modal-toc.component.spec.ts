import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BookModalTocComponent } from './book-modal-toc.component';

describe('BookModalTocComponent', () => {
  let component: BookModalTocComponent;
  let fixture: ComponentFixture<BookModalTocComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BookModalTocComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BookModalTocComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
