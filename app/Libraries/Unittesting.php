<?php

namespace app\Libraries;

class Unittesting
{
    function __construct(){
        assert_options(ASSERT_ACTIVE, 1);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_QUIET_EVAL, 1);
        
        assert_options(ASSERT_CALLBACK, 'assert_failure');
        
    }
    
    function assert_failure($file, $line, $code, $msg)
    {
        echo "<table style='width:50%' border='1'>
<tr><td>Message</td><td>$msg</td></tr>
<tr><td>Status</td><td style='background-color:rgb(255,0,0)'>Failed</td></tr>
</table>";
        
    }
    
    function assert_success($msg)
    {
        echo "<table style='width:50%' border='1'>
<tr><td>Message</td><td>$msg</td></tr>
<tr><td>Status</td><td style='background-color:rgb(0,255,0)'>Success</td></tr>
</table>";
    }
} 

