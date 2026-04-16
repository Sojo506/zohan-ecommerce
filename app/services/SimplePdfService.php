<?php

class SimplePdfService
{
    private const PAGE_WIDTH = 595;
    private const PAGE_HEIGHT = 842;
    private const LEFT_MARGIN = 48;
    private const TOP_MARGIN = 794;
    private const BOTTOM_MARGIN = 60;
    private const LINE_HEIGHT = 16;
    private const BODY_FONT_SIZE = 11;
    private const TITLE_FONT_SIZE = 18;
    private const SECTION_FONT_SIZE = 13;
    private const MAX_CHARS_PER_LINE = 88;

    public function download(string $filename, string $title, array $sections): void
    {
        $pages = $this->buildPages($title, $sections);
        $pdf = $this->renderPdf($pages);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));

        echo $pdf;
    }

    private function buildPages(string $title, array $sections): array
    {
        $pages = [];
        $pageNumber = 1;
        $currentPage = $this->newPage($title, $pageNumber);
        $currentY = self::TOP_MARGIN - 56;

        foreach ($sections as $section) {
            $sectionTitle = strtoupper((string) ($section['title'] ?? 'SECCION'));
            $sectionLines = $section['lines'] ?? [];

            // Si ya no cabe otra sección completa, abre una nueva página antes del encabezado.
            if ($currentY <= self::BOTTOM_MARGIN + 40) {
                $pages[] = $currentPage;
                $pageNumber++;
                $currentPage = $this->newPage($title, $pageNumber);
                $currentY = self::TOP_MARGIN - 56;
            }

            $currentPage[] = [
                'font' => 'F2',
                'size' => self::SECTION_FONT_SIZE,
                'x' => self::LEFT_MARGIN,
                'y' => $currentY,
                'text' => $sectionTitle
            ];
            $currentY -= self::LINE_HEIGHT + 4;

            foreach ($sectionLines as $line) {
                foreach ($this->wrapText((string) $line) as $wrappedLine) {
                    if ($currentY <= self::BOTTOM_MARGIN) {
                        $pages[] = $currentPage;
                        $pageNumber++;
                        $currentPage = $this->newPage($title, $pageNumber);
                        $currentY = self::TOP_MARGIN - 40;
                    }

                    $currentPage[] = [
                        'font' => 'F1',
                        'size' => self::BODY_FONT_SIZE,
                        'x' => self::LEFT_MARGIN,
                        'y' => $currentY,
                        'text' => $wrappedLine
                    ];
                    $currentY -= self::LINE_HEIGHT;
                }
            }

            $currentY -= 8;
        }

        $pages[] = $currentPage;

        return $pages;
    }

    private function newPage(string $title, int $pageNumber): array
    {
        return [
            [
                'font' => 'F2',
                'size' => self::TITLE_FONT_SIZE,
                'x' => self::LEFT_MARGIN,
                'y' => self::TOP_MARGIN,
                'text' => $title
            ],
            [
                'font' => 'F1',
                'size' => 10,
                'x' => self::LEFT_MARGIN,
                'y' => self::TOP_MARGIN - 22,
                'text' => 'Generado: ' . date('d/m/Y H:i')
            ],
            [
                'font' => 'F1',
                'size' => 10,
                'x' => self::PAGE_WIDTH - 110,
                'y' => self::TOP_MARGIN - 22,
                'text' => 'Pagina ' . $pageNumber
            ]
        ];
    }

    private function wrapText(string $text): array
    {
        $cleanText = trim((string) preg_replace('/\s+/', ' ', $text));

        if ($cleanText === '') {
            return [' '];
        }

        return explode("\n", wordwrap($cleanText, self::MAX_CHARS_PER_LINE, "\n", true));
    }

    private function renderPdf(array $pages): string
    {
        $objects = [];
        $pageObjectNumbers = [];

        // Construye manualmente los objetos PDF mínimos: catálogo, páginas y fuentes.
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
        $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>';

        $nextObject = 5;

        foreach ($pages as $page) {
            $pageObjectNumber = $nextObject++;
            $contentObjectNumber = $nextObject++;
            $pageObjectNumbers[] = $pageObjectNumber;

            $contentStream = $this->buildContentStream($page);

            $objects[$pageObjectNumber] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %d %d] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>',
                self::PAGE_WIDTH,
                self::PAGE_HEIGHT,
                $contentObjectNumber
            );

            $objects[$contentObjectNumber] = sprintf(
                "<< /Length %d >>\nstream\n%s\nendstream",
                strlen($contentStream),
                $contentStream
            );
        }

        $objects[2] = sprintf(
            '<< /Type /Pages /Count %d /Kids [%s] >>',
            count($pageObjectNumbers),
            implode(' ', array_map(fn($num) => $num . ' 0 R', $pageObjectNumbers))
        );

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0 => 0];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $maxObject = max(array_keys($objects));

        $pdf .= "xref\n";
        $pdf .= '0 ' . ($maxObject + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $maxObject; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }

        $pdf .= "trailer\n";
        $pdf .= '<< /Size ' . ($maxObject + 1) . ' /Root 1 0 R >>' . "\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefOffset . "\n";
        $pdf .= "%%EOF";

        return $pdf;
    }

    private function buildContentStream(array $lines): string
    {
        $commands = ['BT'];

        // Cada línea se transforma en comandos PDF de fuente, posición y texto.
        foreach ($lines as $line) {
            $commands[] = sprintf('/%s %d Tf', $line['font'] ?? 'F1', (int) ($line['size'] ?? self::BODY_FONT_SIZE));
            $commands[] = sprintf('1 0 0 1 %d %d Tm', (int) ($line['x'] ?? self::LEFT_MARGIN), (int) ($line['y'] ?? self::TOP_MARGIN));
            $commands[] = '(' . $this->escapePdfText((string) ($line['text'] ?? '')) . ') Tj';
        }

        $commands[] = 'ET';

        return implode("\n", $commands);
    }

    private function escapePdfText(string $text): string
    {
        // PDF básico no maneja UTF-8 de forma nativa, así que se convierte y se escapan caracteres reservados.
        $encoded = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);

        if ($encoded === false) {
            $encoded = utf8_decode($text);
        }

        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $encoded
        );
    }
}
