<?php

class LayoutHook
{

    public function default()
    {
        $CI = & get_instance();
        $CI->load->layout = $CI->config->item('default_layout');
    }

}
