<?php
set_include_path(dirname(__FILE__) . '/../src/' . PATH_SEPARATOR . get_include_path());

require_once 'Ilib/ClassLoader.php';

class FileImportTests extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
    }
    
    function testConstruct() 
    {
        $fi = new Ilib_FileImport;
        $this->assertEquals('Ilib_FileImport', get_class($fi)); 
    }
    
    function testCreateCSVParser() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        $this->assertEquals('Ilib_FileImport_Parser_CSV', get_class($parser)); 
    }
    
    function testParseOnInValidFile() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $this->assertFalse($parser->parse('invalid_file.csv'));
    }

    function testParseOnValidFile() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $this->assertTrue(is_array($parser->parse(dirname(__FILE__) . '/contacts_example.csv')));
    }
    
    function testSetParseConfig() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        $this->assertEquals(array('fields' => 10, 'sep' => ';', 'quote' => '\''), $parser->setParseConfig(10, ';', '\''));
    }
    
    function testGetParseConfigAfterParse() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $parser->setParseConfig(NULL, ';', '\'');
        $parser->parse(dirname(__FILE__) . '/contacts_example.csv');
        $this->assertEquals(array('fields' => 37, 'sep' => ';', 'quote' => '\''), $parser->getParseConfig());
    }
    
    /**
     * This test fails until Bug #5257 is fixed!
     */
    function testParseOneLine() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $data = $parser->parse(dirname(__FILE__) . '/contacts_example.csv', 3, 1);
        
        $result = array(
            0 => array(
                0 => '',
                1 => '',
                2 => 'Hans Jensen',
                3 => '',
                4 => 'hans@example.dk',
                5 => '',
                6 => '12345674',
                7 => '12345674',
                8 => '',
                9 => '',
                10 => '',
                11 => 'Vej 4',
                12 => '',
                13 => 'Århus V',
                14 => '',
                15 => '8210',
                16 => 'Danmark',
                17 => '',
                18 => '',
                19 => '',
                20 => '',
                21 => '',
                22 => '',
                23 => '',
                24 => '',
                25 => '',
                26 => '',
                27 => '',
                28 => '',
                29 => '',
                30 => '',
                31 => '',
                32 => '',
                33 => '',
                34 => '',
                35 => '',
                36 => ''
            )
        );
        $this->assertEquals($result, $data);
    }
    
    function testAssignFieldNames() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $field_names = array('', '', 'name', '', 'email', '', 'phone');
        $this->assertTrue($parser->assignFieldNames($field_names));
    }
    
    function testParseAfterAssignFieldNames() 
    {
        $fi = new Ilib_FileImport;
        $parser = $fi->createParser('CSV');
        
        $field_names = array('', '', 'name', '', 'email', '', 'phone');
        $parser->assignFieldNames($field_names);
        $data = $parser->parse(dirname(__FILE__) . '/contacts_example.csv', 0, 3);
        
        /**
         * @Todo: Update test with new example file!
         */
        
        $result = array(
            0 => array(
                'name' => 'Jens Jensen',
                'email' => 'jens@example.dk',
                'phone' => '12345671'
            ),
            1 => array(
                'name' => 'Søren Jensen',
                'email' => 'soren@example.dk',
                'phone' => '12345672'
            ),
            2 => array(
                'name' => 'Peter Jensen',
                'email' => 'peter@example.dk',
                'phone' => '12345673'
           )
        );
        
        $this->assertEquals($result, $data);
    }
}
