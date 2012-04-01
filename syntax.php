<?php
/**
 * Plugin chiplink: Provides automatic links to HTML reports on your server, by searching the wikitext.
 *
 *  Thanks to Gregg Berkholtz for the rtlink plugin, on which this plugin is based.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Jonas Berg
 */

 // must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';
 
class syntax_plugin_chiplink extends DokuWiki_Syntax_Plugin {

    public function getInfo(){
        return array(
            'author' => 'Jonas Berg',
            'email'  => 'jonas.s.berg@home.se',
            'date'   => '2012-03-28',
            'name'   => 'chiplink plugin',
            'desc'   => 'Provides automatic links to HTML reports on your server, by searching the wikitext.',
            'url'    => 'http://www.dokuwiki.org/plugin:chiplink',
        );
    }
    
    public function getType(){
        return 'substition';
    }

    public function getSort(){
        return 920; 
    }

    /**
     * Search for the pattern
     */
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('[sS][wW][0-9]+[pP][0-9]+', $mode, 'plugin_chiplink'); // For example 'SW1613p8'
    }

    /**
     * Read the information from the matched wiki text
     */
    public function handle($match, $state, $pos, &$handler){
        $stripped = substr($match, 2); // Remove initial 'SW'
        $splitted = preg_split("/[pP]/", $stripped); // Split '1613p8' into '1613' and '8'.
        list($wafer, $chip) = $splitted;

        return array($wafer, $chip);
    }

    /**
     * Generate HTML output
     */
    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml'){
            return false;
        }
        
        list($wafer, $chip) = $data;
        $relativelocation = $this->getConf('relativelocation');
        
        $url = "/".$relativelocation."/"."SW".$wafer.".htm#p".$chip; // Relative link, servername is not necessary.
        $description = "SW".$wafer."p".$chip;
        
        $renderer->doc .= "<a href=\"".$url."\">".$description."</a>";
        return true;
    }
}

// vim:ts=4:sw=4:et:
