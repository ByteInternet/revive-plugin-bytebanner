<?php

require_once MAX_PATH . '/lib/OA.php';
require_once LIB_PATH . '/Extension/bannerTypeHtml/bannerTypeHtml.php';
require_once MAX_PATH . '/lib/max/Plugin/Common.php';
require_once MAX_PATH . '/lib/max/Plugin/Translation.php';

/**
 *
 * @package    OpenXPlugin
 * @subpackage Plugins_BannerTypes
 * @author     Maarten van Schaik <maarten@byte.nl>
 * @abstract
 */
class Plugins_BannerTypeHTML_byteHtml_genericHtml extends Plugins_BannerTypeHTML
{
    /**
     * Return type of plugin
     *
     * @return string A string describing the type of plugin.
     */
    function getOptionDescription()
    {
        return $this->translate("Byte Generic HTML Banner");
    }

    function buildForm(&$form, $row)
    {
        parent::buildForm($form, $row);
    }

}

