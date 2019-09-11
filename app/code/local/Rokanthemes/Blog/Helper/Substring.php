<?php

class Rokanthemes_Blog_Helper_Substring extends Varien_Object
{
    const ALLOWED_TAGS = 'a,img,ol,ul,li,p,span,div,b,h1,h2,h3,h4,h5,i,u,strong,iframe,br';

    protected $_input = null;
    protected $_allowedTags = array();
    protected $_tagStack = array();
    protected $_symbolsCount = 0;
    public $readMore = '...';
    protected $_enc = 'UTF-8';

    protected function _construct()
    {
        if ($this->getInput()) {
            $this->_input = $this->getInput();
            $this->_enc = mb_detect_encoding($this->_input);
        }

        if ($this->getAllowedTags() && is_array($this->getAllowedTags())) {
            $this->_allowedTags = $this->getAllowedTags();
        } else {
            $this->_allowedTags = explode(",", self::ALLOWED_TAGS);
        }
        $this->_stripTags();
    }

    private function _stripTags()
    {
        $allowedTags = '';
        foreach ($this->_allowedTags as $tag) {
            $allowedTags .= "<{$tag}>";
        }
        $this->_input = strip_tags($this->_input, $allowedTags);
        $this->_input = preg_replace("#[\r\n]#is", "", $this->_input);
        $this->_input = trim($this->_input);
    }

    private function _getClosingTags()
    {
        $closingTags = '';
        foreach ($this->_tagStack as $key => $val) {
            if ($val != '/>') {
                $closingTags .= $val;
            }
        }
        return $closingTags;
    }

    public function getSymbolsCount()
    {
        return $this->_symbolsCount;
    }

    public function getHtmlSubstr($length)
    {
        $content = '';
        $insideTag = false;
        $force = false;

        for ($i = 0; $i <= mb_strlen($this->_input, $this->_enc); $i++) {
            $char = @$this->_charAt($i);
            $skipCurrent = false;
            if ($char == '<' && @$this->_charAt($i + 1) != '/') {
                if ($tag = $this->_getTag($i)) {
                    array_unshift($this->_tagStack, $tag["closedTag"]);
                    $insideTag = true;
                }
            }

            if ($char == '<' && @$this->_charAt($i + 1) == '/' && $this->_getTag($i + 1)) {
                $insideTag = true;
            } else {
                if ($char == '/' && @$this->_charAt($i - 1) == '<' && $this->_getTag($i)) {
                    $insideTag = true;
                }
            }

            if ($char == '>') {
                if ($tag = $this->_getTag($i, 'down')) {
                    if (
                        (@$this->_charAt($tag["position"] - 1)) == '/'
                        && (@$this->_charAt($tag["position"] - 2)) == '<'
                    ) {
                        foreach ($this->_tagStack as $needle) {
                            if ($needle == '/>') {
                                array_shift($this->_tagStack);
                                continue;
                            }
                            if ($tag["closedTag"] == $needle) {
                                $needle = array_shift($this->_tagStack);
                            }
                            break;
                        }
                    }
                }

                $insideTag = false;
                $skipCurrent = true;
            }

            /* Check escape sequences */
            if ($char == '&' && !$insideTag) {
                $incr = 0;
                do {
                    $escPos = $i + $incr;
                    $bool = @$this->_charAt($escPos) !== ';';
                    $incr++;
                } while ($bool === true && $escPos < mb_strlen($this->_input, $this->_enc));

                $i = $escPos - 1;
                $force = true;
                continue;
            }

            if ((!$insideTag && !$skipCurrent) || ($force)) {
                $this->_symbolsCount++;
                $force = false;
            }

            if ($this->_symbolsCount >= $length || $i >= mb_strlen(@$this->_input, $this->_enc) - 1) {
                $realPosition = $i;
                $closingTags = $this->_getClosingTags();
                $content = mb_substr(@$this->_input, 0, ($realPosition + 1), $this->_enc)
                    . ($this->_symbolsCount >= $length ? $this->readMore : null) . $closingTags
                ;
                break;
            }
        }
        return $content;
    }

    private function _charAt($i, $corr = 0)
    {
        return mb_substr($this->_input, $i, 1, $this->_enc);
    }

    private function _getTag($i, $direction = 'up')
    {
        $tag = null;
        for ($z = 1; $z < 10; $z++) {
            if ($direction == 'up') {
                $tag .= mb_strtolower(@$this->_charAt($i + $z));
                $afterTagSym = @$this->_charAt($i + $z + 1);
                $symbol1 = ' ';
                $symbol2 = '>';
                $position = $i + $z;
            } else {
                $tag = @strrev($tag);
                $tag .= mb_strtolower(@$this->_charAt($i - $z));
                $tag = strrev($tag);
                $afterTagSym = @$this->_charAt($i - $z - 1);
                $symbol1 = '<';
                $symbol2 = '/';
                $position = $i - $z;
            }
            if (in_array($tag, $this->_allowedTags) && ($afterTagSym == $symbol1 || $afterTagSym == $symbol2)) {
                return array(
                    "tag"       => $tag,
                    "position"  => $position,
                    "closedTag" => ($tag == "img" || $tag == 'br' ? "/>" : "</{$tag}>")
                );
            }
        }
        return false;
    }
}