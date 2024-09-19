<?php
class syntax_plugin_custommeta extends DokuWiki_Syntax_Plugin
{
    function getType()
    {
        return 'substition';
    }

    function getSort()
    {
        return 35;
    }

    function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('\+\+CMV:.*?\+\+',$mode,'plugin_custommeta');
        $this->Lexer->addSpecialPattern('\+\+CMI:.*?\+\+',$mode,'plugin_custommeta');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler)
    {   
        
        $match = substr($match,4,-2);
        $pairs = explode(':', $match);
        $mode = trim($pairs[0]);
        $key = trim($pairs[1]);
        $value = trim($pairs[2]);
        if (strlen($key) >= 1 && strlen($value) >1 && ( $mode == 'I' || $mode == 'V' )) {
            $data['mode'] = $mode;
            $data['key'] = $key;
            $data['value'] = $value;
            $data['dataType'] = isset($pairs[3]) ? trim($pairs[3]) : 's';
        }
        return $data;
    }

    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode == 'xhtml') {
            if ($data['mode'] == 'V') {
                $renderer->doc .= $data['value'];
            }
        } elseif ($mode == 'metadata') {
            if ($data['dataType'] == 'i') {
                $renderer->meta['custommeta'][$data['key']] = intval($data['value']);
            } elseif ($data['dataType'] == 'd') {
                $auxDateTime = new DateTime($data['value']);
                $renderer->meta['custommeta'][$data['key']] = $auxDateTime->getTimestamp();
            } else {
                $renderer->meta['custommeta'][$data['key']] = $data['value'];
            }
        }
        
    }
}

