<?php 
defined('_JEXEC') or die;

class plgSystemMapslanguageswitcherInstallerScript {

    function postflight($type, $parent) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__extensions')->set('enabled=1')->where('type=' . $db->q('plugin'))->where('element=' . $db->q('mapslanguageswitcher'));
        $db->setQuery($query)->execute();

    }
}