<?php

class PartialView
{
    private string $source;
    public function __construct($source)
    {
        $this->source = str_replace(".php", "", $source);
    }
    public function render($parameters = array())
    {
        $content = file_get_contents(__DIR__ . "/../views/" . $this->source . ".php");
        $matches = array();
        if (preg_match_all("/\{\{[a-zA-Z0-9]+\}\}/", $content, $matches)) {
            if (!isset($matches[0])) {
            } else {
                foreach ($matches[0] as $match) {
                    if (preg_match_all("/[a-zA-Z0-9]+/", $match, $names)) {
                        if (!isset($names[0])) {
                        } else {
                            foreach ($names[0] as $name) {
                                if (isset($parameters[$name])) {
                                    $content = str_replace($match, $parameters[$name], $content);
                                }
                            }
                        }
                    }
                }
            }
        }
        echo $content;
    }
}
