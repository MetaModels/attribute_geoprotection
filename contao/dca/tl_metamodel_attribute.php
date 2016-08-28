<?php

/**
 * This file is part of MetaModels/attribute_alias.
 *
 * (c) 2012-2016 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeGeoProtection
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     David Maack <david.maack@arcor.de>
 * @author     Andreas Isaak <info@andreas-isaak.de>
 * @author     David Greminger <david.greminger@1up.io>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['geoprotection extends _simpleattribute_'] = array(
    '+display' => array('geoprotection after description'),
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['geoprotection'] = array(
    'label'                 => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['geoprotection'],
    'exclude'               => true,
    'inputType'             => 'checkbox',
    'eval'                  => array(
        'doNotSaveEmpty' => true,
        'alwaysSave'     => true,
        'multiple'       => true,
    ),
);
