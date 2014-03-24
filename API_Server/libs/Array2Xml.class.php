<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/24/14
 * Time: 3:08 PM
 */

class Array2Xml extends DOMDocument{
    public $nodeName;
    private $xpath;
    private $root;
    private $node_name;

    /*
     * Construct
     * Set up the DOM environment
     *
     * @params string root          The name of the root node
     * @params string node_name     The name numeric keys are called
     *
     * */
    public function __construct($root = 'root',$node_name = 'node'){
        parent::__construct();

        //set the encoding
        $this->encoding = "utf-8";
        //format the output
        $this->formatOutput = true;

        //set the node names
        $this->node_name = $node_name;

        //create the root element
        $this->root = $this->appendChild($this->createElement($root));

        $this->xpath = new DOMXPath($this);
    }

    /*
     * create XML reprensentation of the array
     *
     * @access public
     * @param array $arr The array to convert
     * @aparam string $node The name given to child nodes when recursing
     *
     * */
    public function createNode($arr,$node = null){
        if(is_null($node)){
            $node = $this->root;
        }
        foreach($arr as $element => $value){
            $element = is_numeric($element)? $this->node_name : $element;

            $child = $this->createElement($element,(is_array($value)? null : $value));
            $node->appendChild($child);

            if(is_array($value)){
                self::createNode($value,$child);
            }
        }
    }

    /*
     * Return the generate XML as a string
     *
     * @access public
     * @return string
     * */
    public function __toString(){
        return $this->saveXMl();
    }

    /*
     * Query() - perform an XPath query on the XML reperentation of the array
     * @param str $query - query to perform
     * @return mixed
     * */
    public function query($query){
        return $this->xpath->evaluate($query);
    }

    /*
     * Xml2array
     * @xmlObj xml object
     * @param array output
     * */
    public static function xml2array($xmlObj,$output = array()){
        foreach((array) $xmlObj as $index => $node){
            $output[$index] = (is_object($node)) ? self::xml2array($node) : $node;
        }
        return $output;
    }

    /*
    * array2csv
    * @param array output
    * */
    public static function array2csv($array){
        $csv = array();
        foreach($array as $item){
            if(is_array($item)){
                $csv[] = self::array2csv($item);
            } else {
                $csv[] = $item;
            }
        }
        return implode(",",$csv);
    }
} 