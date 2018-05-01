<?php
declare(strict_types = 1);

namespace Bean\Component\CreativeWork\Model;

interface FileFormatType {
	const PDF = 'application/pdf';
	const PDF_VND_CUPS = 'application/vnd.cups-pdf';
	const KINDLE = 'vnd.amazon.mobi8-ebook';
	const EPUB = 'application/epub+zip';
}