<?php
/**
 * Parses a CSV file
 */
require_once 'File/CSV/EmptyFirstFieldBugFix.php';

class Ilib_FileImport_Parser_CSV
{
    /**
     * @var object error
     */
    public $error;

    /**
     * @var array $config contains the config of the file
     */
    protected $config = array();

    /**
     * @var array field names to return
     */
    protected $field_names = array();

    public function getError()
    {
        if ($this->error) {
            return $this->error;
        }
        return ($this->error = new Ilib_Error);
    }

    /**
     * set parse config
     *
     * @param integer $fields the number of fields
     * @param string $sep the seperator character, default ';'
     * @param string $quote the quoter delimiter, default '"'
     *
     * @return array with the config
     */
    public function setParseConfig($fields = null, $sep = ';', $quote = '"')
    {
        if ($fields != null) {
            $this->config['fields'] = $fields;
        }
        $this->config['sep'] = $sep;
        $this->config['quote'] = $quote;
        return $this->config;
    }

    /**
     * returns the parse config
     *
     * @return array with the parse config
     */
    public function getParseConfig()
    {
        return $this->config;
    }

    /**
     * Assigns fieldnames which the parse will use in return array.
     *
     * @param array names with the array keys to be used in parse return array.
     * @return boolean true or false
     */
    public function assignFieldNames($names)
    {
        if (empty($names) || !array($names)) {
            throw new Exception('the array with names was not an array or empty');
        }

        $this->field_names = $names;
        return true;

    }

    /**
     * parse a csv file
     *
     * @param string filepath the path for the file to be parsed
     * @param integer offset the line number offset to read from
     * @param integer $lines the number of lines that you want to have parsed
     */
    public function parse($file, $offset = 0, $lines = null)
    {
        if (!file_exists($file)) {
            $this->getError()->set('invalid file');
            return false;
        }

        $config = File_CSV_EmptyFirstFieldBugFix::discoverFormat($file);
        if (PEAR::isError($config)) {
            $this->getError()->set('unable to discover config');
            throw new Exception($config->getUserInfo());
        }
        $this->config = array_merge($config, $this->config);

        $file_csv_config = $this->config;
        $file_csv_config['header'] = false;

        if ($lines != null) {
            $lines += $offset;
        }
        $data = array();
        $i = 0;

        // when fx in en tests the file is read several times after each other it needs to be reses before
        File_CSV_EmptyFirstFieldBugFix::resetPointer($file, $file_csv_config, FILE_MODE_READ);
        while (($res = File_CSV_EmptyFirstFieldBugFix::readQuoted($file, $file_csv_config)) && ($lines == null || $i < $lines)) {
            if ($i >= $offset) {
                if (!empty($this->field_names)) {
                    $n = 0;
                    $named_res = array();
                    foreach ($this->field_names as $name) {
                        // only fiels names that is not empty is given in the result
                        if (!empty($name)) {
                            $named_res[$name] = $res[$n];
                        }
                        $n++;
                    }
                    $data[] = $named_res;
                } else {
                    $data[] = $res;
                }
            }
            $i++;
        }

        return $data;
    }
}
