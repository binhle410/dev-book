import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BookContactComponent } from './book-contact.component';

describe('BookContactComponent', () => {
  let component: BookContactComponent;
  let fixture: ComponentFixture<BookContactComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BookContactComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BookContactComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
