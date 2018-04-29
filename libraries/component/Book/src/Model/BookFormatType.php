<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

interface BookFormatType {
	const AUDIO_BOOK = 'Audiobook';
	const EBOOK = 'EBook';
	const HARD_COVER = 'Hardcover';
	const PAPER_BACK = 'Paperback';
}