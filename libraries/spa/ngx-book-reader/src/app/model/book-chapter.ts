export class BookChapter {
    id: number;
    position: number;
    name: string;
    content: string;
    parent: BookChapter;
    children: BookChapter[];
}
