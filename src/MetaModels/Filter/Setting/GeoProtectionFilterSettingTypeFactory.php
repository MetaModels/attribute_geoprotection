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
 * @package     MetaModels
 * @subpackage  AttributeGeoProtection
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Maack <david.maack@arcor.de>
 * @copyright   2012-2016 The MetaModels team.
 * @license     https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Filter\Setting;

/**
 * Attribute type factory for tags filter settings.
 */
class GeoProtectionFilterSettingTypeFactory extends AbstractFilterSettingTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this
            ->setTypeName('geoprotection')
            ->setTypeIcon('system/modules/geoprotection/html/filter_tags.png')
            ->setTypeClass('MetaModels\Filter\Setting\GeoProtection')
            ->allowAttributeTypes();

        foreach (array(
                     'geoprotection'
                 ) as $attribute) {
            $this->addKnownAttributeType($attribute);
        }
    }
}
