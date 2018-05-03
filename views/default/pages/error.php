<?php
/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package WAF
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */
?>
<p>There's been an error.</p>
<?php
if (isset($_SESSION['exception']))
{
    if (_DEBUG)
    {
        echo '<pre>';
        print_r($_SESSION['exception']);
        unset($_SESSION['exception']);   
        echo '</pre>';
    }
}
?>
