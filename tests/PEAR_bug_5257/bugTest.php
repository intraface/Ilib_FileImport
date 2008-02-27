<?php
class bugTest extends PHPUnit_Framework_TestCase
{    
    function testParseFileStartingWithADelimiter() {
        require_once 'File/CSV.php';
        $config = File_CSV::discoverFormat('example.csv');
        while ($res = File_CSV::readQuoted('example.csv', $config)) 
            $result[] = $res;
        
        $expected = array(
            0 => array(0 => 'Field 0', 1 => 'Field 1', 2 => 'Field 2', 3 => 'Field 3', 4 => 'Field 4'),        
            1 => array(0 => '',1 => 'Field 1',2 => '',3 => 'Field 3',4 => ''));
        $this->assertEquals($expected, $result);
    }
}
?>
