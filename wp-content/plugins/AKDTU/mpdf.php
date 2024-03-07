<?php

# define(MPDF_ROOT, __DIR__ . '/../../../wiki/lib/plugins/dw2pdf/vendor/mpdf/mpdf/');
require_once __DIR__ . '/../../../wiki/lib/plugins/dw2pdf/DokuImageProcessorDecorator.php';
require_once __DIR__ . '/../../../wiki/lib/plugins/dw2pdf/vendor/autoload.php';

/**
 * Class DokuPDF
 * Some DokuWiki specific extentions
 */
class AKDTUpdf extends \Mpdf\Mpdf
{

    /**
     * DokuPDF constructor.
     *
     * @param string $pagesize
     * @param string $orientation
     * @param int $fontsize
     */
    function __construct($pagesize = 'A4', $orientation = 'portrait', $fontsize = 11, $margin_left = 15, $margin_bottom = 16, $margin_right = 15, $margin_top = 16, $dpi = 300)
    {

        $format = $pagesize;

		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		$mode = '';

        # we're always UTF-8
        parent::__construct(
            array(
                'mode' => $mode,
                'format' => $format,
				'orientation' => $orientation,
                'default_font_size' => $fontsize,
                'ImageProcessorClass' => DokuImageProcessorDecorator::class,
				'fontDir' => array_merge($fontDirs, [
					__DIR__ . '/fonts',
				]),
				'fontdata' => $fontData + [
					"calibri" => [
						'R' => 'calibri.ttf',
					],
					"calibri-bold" => [
						'R' =>  'calibri-bold.ttf',
					],
					# "robotoblack" => [
					# 	'R' => 'Roboto-Black.ttf',
					# 	'I' => 'Roboto-BlackItalic.ttf',
					# ]
				],
				'dpi' => $dpi,
				'margin_left' => $margin_left,
				'margin_right' => $margin_right,
				'margin_top' => $margin_top,
				'margin_bottom' => $margin_bottom,
            )
        );

        $this->autoScriptToLang = true;
        $this->baseScript = 1;
        $this->autoVietnamese = true;
        $this->autoArabic = true;
        $this->autoLangToFont = true;

        $this->ignore_invalid_utf8 = true;
        $this->tabSpaces = 4;

        # assumed that global language can be used, maybe Bookcreator needs more nuances?
        $this->SetDirectionality('ltr');
    }

    /**
     * Destructor
     */
    function __destruct()
    {
        
    }
}


?>