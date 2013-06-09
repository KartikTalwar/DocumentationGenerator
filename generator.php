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
        $out .= $this->_n();

        return $out;
    }


    private function make_notes()
    {
        $out  = $this->_h(3, 'Notes');
        $out .= $this->_list($this->data->additional_notes);
        $out .= $this->_n();

        return $out;
    }


    private function make_parameters()
    {
        $out  = $this->_h(2, 'Parameters');
        $out .= $this->make_call();
        $out .= $this->handle_input_params($this->data->method_parameters);
        $out .= $this->handle_filter_params($this->data->method_parameters);

        return $out;
    }


    private function handle_input_params($arr)
    {
        $out = $this->_h(3, 'Input');

        foreach($arr as $i)
        {
            if($i->type == 'input')
            {
                $out .= '- **'.$i->parameter.'** - '.$i->description.'  ';
                $out .= ($i->is_required) ? '*(required)*' : '*(optional)*' ;
                $out .= $this->_n(1);

                if($i->parameter == 'format')
                {
                    $out .= $this->_list($this->data->response_formats, 2);
                }
            }
        }

        return $out.$this->_n();
    }


    private function handle_filter_params($arr)
    {
        $out = $this->_h(3, 'Filter');

        foreach($arr as $i)
        {
            if($i->type == 'filter')
            {
                $out .= '- **'.$i->parameter.'** - '.$i->description. '  ';
                $out .= ($i->is_required) ? '*(required)*' : '*(optional)*' ;
                $out .= $this->_n(1);
            }
        }

        return $out.$this->_n();
    }


    private function make_sources()
    {
        $out  = $this->_h(3, 'Sources');
        $out .= $this->_list($this->data->data_source);
        $out .= $this->_n();

        return $out;
    }


    private function make_summary()
    {
        $out  = $this->_h(2, 'Summary');
        $out .= "<table>\n";
        $out .= $this->_row('Name', 'Value', true);
        $out .= $this->_row('Request Protocol', $this->data->request_protocol);
        $out .= $this->_row('Method ID', $this->data->method_id);
        $out .= $this->_row('Service Name', $this->data->service_name);
        $out .= $this->_row('Service ID', $this->data->service_id);
        $out .= $this->_row('Requires API Key', ($this->data->is_authenticated) ?  'Yes' : 'No');
        $out .= $this->_row('Cache Time', $this->data->cache_time_s.' seconds');
        $out .= $this->_row('Information Steward', $this->data->information_steward);
        $out .= $this->_row('Data Type', $this->data->data_type);
        $out .= $this->_row('Update Frequency', $this->data->update_frequency);
        $out .= "</table>\n";
        $out .= $this->_n();

        return $out;
    }


    private function _row($l, $r, $b_all=false)
    {
        if($b_all)
        {
            $r = '<b>'.$r.'</b>';
        }

        $out  = "  <tr>\n    <td><b>$l</b></td>\n";
        $out .= "    <td>$r</td>\n  </tr>\n";

        return $out;
    }


    private function make_examples()
    {
        $out  = $this->_h(2, 'Examples');
        $out .= $this->make_call();
        $out .= $this->handle_urls();
        $out .= $this->_n();

        return $out;
    }


    private function handle_urls()
    {
        $out  = '```'.$this->_n(1);

        foreach($this->data->request_examples as $i)
        {
            $out .= $i.$this->_n(1);
        }

        $out .= '```';
        return $out;
    }


    private function _list($arr, $pad=0)
    {
        $out = '';

        foreach($arr as $i)
        {
            $out .= str_repeat(' ', $pad).'- '. $i.$this->_n(1);
        }

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
        $md .= $this->make_summary();
        $md .= $this->make_notes();
        $md .= $this->make_sources();
        $md .= $this->make_parameters();
        $md .= $this->make_examples();

        return $md;
    }

}


$schema = 'schema.json';
$generator = new DocGenerator($schema);

print_r($generator->compile());


?>
