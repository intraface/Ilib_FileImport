<?php
/**
 * Imports a file and returns the result as array
 */
require_once('Ilib/Error.php'); 

class Ilib_FileImport {
      
    
    /**
     * constructor
     */
    public function __construct() 
    {
        
        $this->error = new Ilib_Error;
        
    }
    
    /**
     * creates and returns a parser object
     * 
     * @param string $parser the name of the parser, eg. CSV
     * 
     * @return mixed parser object on success and false on failure
     */
    public function createParser($parser) 
    {
        if(!ereg("^[a-zA-Z0-9]*$", $parser)) {
            trigger_error("parser name must only contain characters and numbers", E_USER_ERROR);
            return false;
        }
        
        $parser_name = 'Ilib_FileImport_Parser_'.$parser;
        $file_name = 'Ilib/FileImport/Parser/'.$parser.'.php';
        require_once $file_name;
        $obj = new $parser_name;
        
        return $obj;
        
    } 
}


?>
