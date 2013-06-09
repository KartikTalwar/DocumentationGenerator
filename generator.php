<?php


class DocGenerator
{

    public $api_prefix;
    public $data_file;


    public function __construct($schema_file)
    {
        $this->data_file = $schema_file;
    }


    private function load_data()
    {
        $get = file_get_contents($this->data_file);
        return $get;
    }


    public function compile()
    {
        return $this->load_data();
    }

}


$schema = 'schema.json';
$generator = new DocGenerator($schema);

print_r($generator->compile());


?>
