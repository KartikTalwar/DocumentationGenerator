<?php


class DocGenerator
{

    public  $api_prefix;
    public  $data_file;
    public  $data;
    private $api_key;


    public function __construct($key) {
        $this->api_prefix = 'https://api.uwaterloo.ca/v2';
        $this->api_key    = $key;
    }


    private function load_data() {
        $get  = file_get_contents($this->data_file);
        $load = json_decode($get);

        $this->data = $load;
    }


    public function make_header() {
        $out  = $this->_h(1, $this->data->method_name);

        return $out;
    }


    public function make_call() {
        $out  = "```\n";
        $out .= $this->data->request_protocol.' '.$this->data->method_url;
        $out .= "\n```";
        $out .= $this->_n();

        return $out;
    }


    public function make_description() {
        $out  = $this->_h(2, 'Description');
        $out .= $this->_q($this->data->method_description);
        $out .= $this->_n();

        return $out;
    }


    public function make_notes() {
        $out  = $this->_h(3, 'Notes');
        $out .= $this->_list($this->data->additional_notes);
        $out .= $this->_list(array('Any value can be `null`'));
        $out .= $this->_n();

        return $out;
    }


    public function make_parameters() {
        $out  = $this->_h(2, 'Parameters');
        $out .= $this->make_call();
        $out .= "<table>\n";
        $out .= $this->_row('Parameter', 'Type', 'Required', 'Description', true);

        foreach($this->data->method_parameters as $param) {
            $out .= $this->_row($param->parameter, $param->type,
                                ($param->is_required) ? $this->_i('yes', false) : $this->_i('no', false),
                                $param->description, false, false);
        }

        $out .= "</table>".$this->_n();
        $out .= $this->_b('Output Formats').$this->_n();
        $out .= $this->_list($this->data->response_formats).$this->_n();

        return $out;
    }


    public function make_sources() {
        $out  = $this->_h(3, 'Sources');
        $out .= $this->_list($this->data->data_source);
        $out .= $this->_n();

        return $out;
    }


    public function make_summary() {
        $out  = $this->_h(2, 'Summary');
        $out .= "<table>\n";
        $out .= $this->_row('Name', 'Value', 'Name', 'Value', true);
        $out .= $this->_row('Request Protocol', $this->data->request_protocol, 'Requires API Key', ($this->data->is_authenticated) ?  'Yes' : 'No');
        $out .= $this->_row('Method ID', $this->data->method_id, 'Enabled', ($this->data->is_enabled) ? 'Yes' : 'No');
        $out .= $this->_row('Service Name', $this->data->service_name, 'Service ID', $this->data->service_id);
        $out .= $this->_row('Information Steward', $this->data->information_steward, 'Data Type', $this->data->data_type);
        $out .= $this->_row('Update Frequency', $this->data->update_frequency, 'Cache Time', $this->data->cache_time_s.' seconds');
        $out .= "</table>\n";
        $out .= $this->_n();

        return $out;
    }


    public function make_response($arr=false, $depth=1) {
        $out  = (!$arr) ? $this->_h(2, 'Response') : '';
        $out .= "<table>\n";
        $out .= (!$arr) ? $this->_row('Field Name', 'Type', 'Value Description', null, true) : '';

        $data = ($arr) ? $arr : $this->data->response_fields;

        foreach($data as $i) {
            if($i->children) {
                $out .= $this->_row($i->field, $i->type, $i->description."<br>".$this->make_response($child, $depth+1));
            } else {
                $out .= $this->_row($i->field, $i->type, ($depth > 4) ? null : $i->description);
            }
        }

        $out .= "</table>\n";

        return $out;
    }


    public function make_output() {
        $out  = $this->_n().'Any value can be `null`'.$this->_n();
        $out .= $this->_h(2, 'Output');
        $out .= $this->_h(4, 'JSON')."```json\n";
        $out .= file_get_contents($this->data->request_examples[0].'?key='.$this->api_key);
        $out .= "\n```".$this->_n();

        return $out;
    }


    public function make_examples() {
        $out .= $this->_h(2, 'Examples');
        $out .= $this->make_call();
        $out .= $this->_list($this->data->request_examples, 0, true);
        $out .= $this->_n();

        return $out;
    }


    public function compile($json_schema) {
        
        $this->data_file = $json_schema;
        $this->load_data();

        $md  = '';
        $md .= $this->make_header();
        $md .= $this->make_call();
        $md .= $this->make_description();
        $md .= $this->make_summary();
        $md .= $this->make_notes();
        $md .= $this->make_sources();
        $md .= $this->make_parameters();
        $md .= $this->make_examples();
        $md .= $this->make_response();
        $md .= $this->make_output();

        return $md;
    }


    private function _row($l, $r, $m=null, $n=null, $b_all=false, $b_third=true) {
        if($b_all) {
            $r = '<b>'.$r.'</b>';
            $m = ($m != null) ? '<b>'.$m.'</b>' : null;
            $n = ($n != null) ? '<b>'.$n.'</b>' : null;
        }

        $x = ($m != null && $n != null) ? '<b>'.$m.'</b>': $m;
        $x = ($b_third) ? $x : $m ;

        $out  = "  <tr>\n    <td><b>$l</b></td>\n";
        $out .= "    <td>$r</td>\n";
        $out .= ($m != null) ? "    <td>$x</td>\n" : '';
        $out .= ($n != null) ? "    <td>$n</td>\n" : '';
        $out .= "  </tr>\n";

        return $out;
    }


    private function _list($arr, $pad=0, $bold=false) {
        $out = '';
        $b   = ($bold) ? '**' : '';

        foreach($arr as $i) {
            $out .= str_repeat(' ', $pad).'- '. $b . $i. $b .$this->_n(1);
        }

        return $out;
    }


    private function _h($i, $str) {
        return str_repeat('#', $i).' '.$str.$this->_n();
    }


    private function _q($str) {
        return '> '.$str;
    }


    private function _n($i=2) {
        return str_repeat("\n", $i);
    }


    private function _b($str) {
        return '**'.$str.'**';
    }


    private function _i($str, $md=true) {
        return ($md) ? '*'.$str.'*' : '<i>'.$str.'</i>';
    }

}



$api_key    = 'yourapikey';
$generator  = new DocGenerator($api_key);
$input_file = $argv[1];

echo $generator->compile($input_file);


?>
