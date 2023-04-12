<?php
/**
 * Plugin Tab: Inserts a pagebreak into the document for every <pagebreak> it encounters.  Based on the tab plugin by Tim Skoch <timskoch@hotmail.com>
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Jonathan McBride and Chris Sturm - The University of Texas at Austin
 *
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_pagebreak extends DokuWiki_Syntax_Plugin {

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
        return 999;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<pagebreak>',$mode,'plugin_pagebreak');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        switch ($state) {
            case DOKU_LEXER_ENTER :
            break;
            case DOKU_LEXER_MATCHED :
            break;
            case DOKU_LEXER_UNMATCHED :
            break;
            case DOKU_LEXER_EXIT :
            break;
            case DOKU_LEXER_SPECIAL :
            break;
        }
        return array();
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        if($mode == 'xhtml'){
            if(is_a($renderer,'renderer_plugin_dw2pdf')){
                $renderer->doc .= "<pagebreak />";
            } else {
                $renderer->doc .= "<br style=\"page-break-after:always;\">";
            }
            return true;
        } else if ($mode == 'odt') {
            $renderer->pagebreak();
            return true;
        }
        return false;
    }
}

