import { TestBed, inject } from '@angular/core/testing';

import { BookChapterService } from './book-chapter.service';

describe('BookChapterService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [BookChapterService]
    });
  });

  it('should be created', inject([BookChapterService], (service: BookChapterService) => {
    expect(service).toBeTruthy();
  }));
});
