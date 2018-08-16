export class BookChapter {
    id: number;
    position: number;
    chapterNumber: number;
    subChapterNumber: number;
    name: string;
    text: string;
    createdAt: Date;
    description: string;
    partOf: any;
    headline: string;
    locale: string;
    about: string;

    parent: BookChapter;
    children: BookChapter[];
}
