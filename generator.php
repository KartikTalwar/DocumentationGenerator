<?php


class DocGenerator
{

    public $api_prefix;
    public $data_file;
    public $data;


    public function __construct($schema_file)
    {
        $this->data_file = $schema_file;
        $this->load_data();
    }


    private function load_data()
    {
        $get  = file_get_contents($this->data_file);
        $load = json_decode($get);

        $this->data = $load;
    }


    private function make_header()
    {
        $out  = $this->_h(1, $this->data->method_name);

        return $out;
    }


    private function make_call()
    {
        $out  = "```\n";
        $out .= $this->data->request_protocol.' '.$this->data->method_url;
        $out .= "\n```";
        $out .= $this->_n();

        return $out;
    }


    private function make_description()
    {
        $out  = $this->_h(2, 'Description');
        $out .= $this->_q($this->data->method_description);

        return $out;
    }


    private function _h($i, $str)
    {
        return str_repeat('#', $i).' '.$str.$this->_n();
    }


    private function _q($str)
    {
        return '> '.$str;
    }


    private function _n($i=2)
    {
        return str_repeat("\n", $i);
    }


    public function compile()
    {
        $md  = '';
        $md .= $this->make_header();
        $md .= $this->make_call();
        $md .= $this->make_description();

        return $md;
    }

}


$schema = 'schema.json';
$generator = new DocGenerator($schema);

print_r($generator->compile());


?>
