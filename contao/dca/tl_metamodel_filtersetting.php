<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package     MetaModels
 * @subpackage  AttributeGeoProtection
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @author      David Maack <david.maack@arcor.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['geoprotection extends default'] = array(
    '+config' => array('gp_attr_id'),
);

$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['gp_attr_id'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['attr_id'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'eval'                    => array(
        'doNotSaveEmpty'      => true,
        'alwaysSave'          => true,
        'submitOnChange'      => true,
        'includeBlankOption'  => true,
        'mandatory'           => true,
        'tl_class'            => 'w50',
    ),
);