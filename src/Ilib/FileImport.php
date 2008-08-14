<?php
/**
 * Imports a file and returns the result as array
 */
class Ilib_FileImport 
{
    /**
     * creates and returns a parser object
     * 
     * @param string $parser the name of the parser, eg. CSV
     * 
     * @return mixed parser object on success and false on failure
     */
    public static function createParser($parser) 
    {
        if(!ereg("^[a-zA-Z0-9]*$", $parser)) {
            throw new Exception("Parser name must only contain characters and numbers");
        }
        
        $parser_name = 'Ilib_FileImport_Parser_'.$parser;
        $file_name = 'Ilib/FileImport/Parser/'.$parser.'.php';
        require_once $file_name;
        $obj = new $parser_name;
        
        return $obj;
    } 
}
