/**
 * @copyright Copyright (C) 2018, OJ Perez
 * @package WAF
 * @version 0.1a
 * @author OJ Perez <oj@ojperez.com>
 */


$(document).ready(function()
{
    $('#acciones').change(function()
    {
        var path = $(this).find('option:selected').data('path');
        if (path!='0')
            window.location.assign('?'+path);
//        window.location.reload();
//            console.log(path);
    });
});