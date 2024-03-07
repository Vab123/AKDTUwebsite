<?php
/**
 * Plugin Tab: Inserts an infobox into the document for every <printable> it encounters. Based on the tab plugin by Tim Skoch <timskoch@hotmail.com>
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Victor Brandsen - AKDTU
 *
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_akdtu extends DokuWiki_Syntax_Plugin {

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }


    /**
     * Where to sort in?
     */
    function getSort(){
        return 32;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<printable>',$mode,'plugin_akdtu');
    }

	public function postConnect() {
		$this->Lexer->addExitPattern('</printable>','plugin_akdtu');
	}

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
		switch ($state) {
			case DOKU_LEXER_ENTER :      return array($state, '');
			case DOKU_LEXER_UNMATCHED :  return array($state, $match);
			case DOKU_LEXER_EXIT :       return array($state, '');
		  }
        return array();
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        global $ID;
        if($mode == 'xhtml'){
			list($state,$match) = $data;

			switch ($state) {
                case DOKU_LEXER_ENTER :      
					// Start-value found. Output first part of string
					
					$meta = p_get_metadata($ID); // Get page meta, so title can be extracted

					// First part of string
                    $renderer->doc .= "<div class=\"wrap_noprint plugin_wrap\"><div class=\"admonition information\"><p class=\"admonition-title\">Info</p><p>Siden her er lavet til at blive eksporteret til PDF, og ser derfor ikke nødvendigvis korrekt ud på computeren. <a href=\"?do=export_pdf"; 
                    break;
 
                case DOKU_LEXER_UNMATCHED :
					$meta = p_get_metadata($ID); // Get page meta, so title can be extracted

                    $settings = explode(",", $match, 2); // Unpack matched values

					$template = $settings[0];

					if (count($settings) > 1) { $title = $settings[1]; } else { $title = $meta['title']; }

					if (strlen($template) == 0) { $template = "AKDTU"; } // Set default value for template

                    $renderer->doc .= $renderer->_xmlEntities("&book_title=" . htmlentities($title) . "&tpl=" . htmlentities($template)); // Output template
                    break;
                case DOKU_LEXER_EXIT :
					// End-value found. Output final part of string

					// Final part of string
					$renderer->doc .= "\"><input style=\"padding: 5px; border: 3px solid #333; background: transparent; color: #333; font-weight: bold; text-transform: uppercase; font-family: sans-serif; cursor: pointer;\" type=\"button\" value=\"Export to PDF\"></a></p></div></div>";
                    break;
            }

            return true;
        }
        return false;
    }
}

