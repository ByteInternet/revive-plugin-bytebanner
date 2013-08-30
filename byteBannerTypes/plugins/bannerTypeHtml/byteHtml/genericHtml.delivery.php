<?php

/**
 * Inject request parameters into the banner
 *
 * Based on Plugin_BannerTypeHTML_delivery_adRender
 *
 * @package    OpenXPlugin
 * @subpackage Plugins_BannerTypes
 * @author     Maarten van Schaik <maarten@byte.nl>
 * @abstract
 */
function Plugin_bannerTypeHtml_byteHtml_genericHtml_Delivery_adRender(&$aBanner, $zoneId=0, $source='', $ct0='', $withText=false, $logClick=true, $logView=true, $useAlt=false, $loc, $referer)
{
    $conf = $GLOBALS['_MAX']['CONF'];
    $prepend = !empty($aBanner['prepend']) ? $aBanner['prepend'] : '';
    $append = !empty($aBanner['append']) ? $aBanner['append'] : '';
    $code = !empty($aBanner['htmlcache']) ? $aBanner['htmlcache'] : '';
    $aBanner['bannerContent'] = $aBanner['htmltemplate'];

    // Replace custom patterns
    $patterns = array();
    $replacements = array();
    foreach ($GLOBALS['_REQUEST'] as $k => $v)
    {
        // Use %%varname%% to replace in HTML
        $patterns[] = "%%$k%%";
        $replacements[] = htmlspecialchars($v);

        // Use ##varname## to replace in URLs
        $patterns[] = "##$k##";
        $replacements[] = urlencode($v);
    }
    $code = str_replace($patterns, $replacements, $code);

    // Parse PHP code
    if ($conf['delivery']['execPhp'])
    {
        if (preg_match ("#(\<\?php(.*)\?\>)#isU", $code, $parser_regs))
        {
            // Extract PHP script
            $parser_php     = $parser_regs[2];
            $parser_result     = '';

            // Replace output function
            $parser_php = preg_replace ("#echo([^;]*);#i", '$parser_result .=\\1;', $parser_php);
            $parser_php = preg_replace ("#print([^;]*);#i", '$parser_result .=\\1;', $parser_php);
            $parser_php = preg_replace ("#printf([^;]*);#i", '$parser_result .= sprintf\\1;', $parser_php);

            // Split the PHP script into lines
            $parser_lines = explode (";", $parser_php);
            for ($parser_i = 0; $parser_i < sizeof($parser_lines); $parser_i++)
            {
                if (trim ($parser_lines[$parser_i]) != '')
                    eval (trim ($parser_lines[$parser_i]).';');
            }

            // Replace the script with the result
            $code = str_replace ($parser_regs[1], $parser_result, $code);
        }
    }

    // Get the text below the banner
    $bannerText = !empty($aBanner['bannertext']) ? "$clickTag{$aBanner['bannertext']}$clickTagEnd" : '';
    // Get the image beacon...
    if ((strpos($code, '{logurl}') === false) && (strpos($code, '{logurl_enc}') === false)) {
        $beaconTag = ($logView && $conf['logging']['adImpressions']) ? _adRenderImageBeacon($aBanner, $zoneId, $source, $loc, $referer) : '';
    } else {
        $beaconTag = '';
    }
    return $prepend . $code . $bannerText . $beaconTag . $append;
}

