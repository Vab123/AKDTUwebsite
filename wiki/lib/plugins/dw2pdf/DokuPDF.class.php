<?php
/**
 * Wrapper around the mpdf library class
 *
 * This class overrides some functions to make mpdf make use of DokuWiki'
 * standard tools instead of its own.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */

use dokuwiki\plugin\dw2pdf\DokuImageProcessorDecorator;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class DokuPDF
 * Some DokuWiki specific extentions
 */
class DokuPDF extends \Mpdf\Mpdf
{

    /**
     * DokuPDF constructor.
     *
     * @param string $pagesize
     * @param string $orientation
     * @param int $fontsize
     */
    function __construct($pagesize = 'A4', $orientation = 'portrait', $fontsize = 11)
    {
        global $conf, $lang;

        if (!defined('_MPDF_TEMP_PATH')) define('_MPDF_TEMP_PATH', $conf['tmpdir'] . '/dwpdf/' . rand(1, 1000) . '/');
        io_mkdir_p(_MPDF_TEMP_PATH);

        $format = $pagesize;
        if ($orientation == 'landscape') {
            $format .= '-L';
        }

        switch ($conf['lang']) {
            case 'zh':
            case 'zh-tw':
            case 'ja':
            case 'ko':
                $mode = '+aCJK';
                break;
            default:
                $mode = 'UTF-8-s';

        }

        // we're always UTF-8
        parent::__construct(
            array(
                'mode' => $mode,
                'format' => $format,
                'default_font_size' => $fontsize,
                'ImageProcessorClass' => DokuImageProcessorDecorator::class,
                'tempDir' => _MPDF_TEMP_PATH, //$conf['tmpdir'] . '/tmp/dwpdf'
				'fontdata' => ((new Mpdf\Config\FontVariables())->getDefaults())['fontdata'] + [ // lowercase letters only in font key
					'roboto' => [
						'R' => 'Roboto-Regular.ttf',
						'I' => 'Roboto-Italic.ttf',
					],
					'robotolight' => [
						'R' => 'Roboto-Light.ttf',
						'I' => 'Roboto-LightItalic.ttf',
					],
					'robotoblack' => [
						'R' => 'Roboto-Black.ttf',
						'I' => 'Roboto-BlackItalic.ttf',
					],
					'robotobold' => [
						'R' => 'Roboto-Bold.ttf',
						'I' => 'Roboto-BoldItalic.ttf',
					],
					'robotoboldcondensed' => [
						'R' => 'Roboto-BoldCondensed.ttf',
						'I' => 'Roboto-BoldCondensedItalic.ttf',
					],
					'robotomedium' => [
						'R' => 'Roboto-Medium.ttf',
						'I' => 'Roboto-MediumItalic.ttf',
					],
					'robotothin' => [
						'R' => 'Roboto-Thin.ttf',
						'I' => 'Roboto-ThinItalic.ttf',
					],
					'robotocondensed' => [
						'R' => 'Roboto-Condensed.ttf',
						'I' => 'Roboto-CondensedItalic.ttf',
					]
				],
				'default_font' => 'roboto',
            )
        );

		$this->AddFontDirectory(__DIR__ . '/tpl/fonts/');;

        $this->autoScriptToLang = true;
        $this->baseScript = 1;
        $this->autoVietnamese = true;
        $this->autoArabic = true;
        $this->autoLangToFont = true;

        $this->ignore_invalid_utf8 = true;
        $this->tabSpaces = 4;

        // assumed that global language can be used, maybe Bookcreator needs more nuances?
        $this->SetDirectionality($lang['direction']);
    }

    /**
     * Cleanup temp dir
     */
    function __destruct()
    {
        io_rmdir(_MPDF_TEMP_PATH, true);
    }

    /**
     * Decode all paths, since DokuWiki uses XHTML compliant URLs
     *
     * @param string $path
     * @param string $basepath
     */
    function GetFullPath(&$path, $basepath = '')
    {
        $path = htmlspecialchars_decode($path);
        parent::GetFullPath($path, $basepath);
    }
}
