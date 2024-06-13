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
     * @param mixed[string] $pagesize
     */
    function __construct($params = array())
    {
		# Default values
		$default = array(
			'pagesize'		=> 'A4',		# Size of the pages created.
			'orientation'	=> 'portrait',	# Orientation of the pages created. 'landscape' or 'portrait'
			'fontsize'		=> 11,			# Default font-size of the document
			'margin_left'	=> 15,			# Margin on the left side of the pages created
			'margin_bottom'	=> 16,			# Margin on the bottom of the pages created
			'margin_right'	=> 15,			# Margin on the right side of the pages created
			'margin_top'	=> 16,			# Margin on the top of the pages created
			'dpi'			=> 300,			# DPI of the pages created
		);

		# Combine default values and provided settings
		$values = shortcode_atts($default, $params);

        $format = $values['pagesize'];

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
				'orientation' => $values['orientation'],
                'default_font_size' => $values['fontsize'],
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
				'dpi' => $values['dpi'],
				'margin_left' => $values['margin_left'],
				'margin_right' => $values['margin_right'],
				'margin_top' => $values['margin_top'],
				'margin_bottom' => $values['margin_bottom'],
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