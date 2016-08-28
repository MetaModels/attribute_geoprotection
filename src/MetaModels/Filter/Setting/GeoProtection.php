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
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Filter\Setting;

use Geolocation;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\SimpleQuery;
use MetaModels\Filter\Rules\StaticIdList;

/**
 * Class GeoProtection.
 *
 * @package MetaModels\Filter\Setting
 */
class GeoProtection extends Simple
{
    /**
     * {@inheritdoc}
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl)
    {
        $objAttribute = $this->getMetaModel()->getAttributeById($this->get('gp_attr_id'));
        if ($objAttribute) {
            $objGeo     = Geolocation::getInstance();
            $arrCountry = $objGeo->getUserGeolocation()->getCountriesShort();
            // Set 'no_country' if no country was found.
            $arrCountry = ($arrCountry) ? $arrCountry : array('xx');

            // Build query string part.
            foreach (array_keys($arrCountry) as $k) {
                $arrCountry[$k] = "find_in_set ('" . $arrCountry[$k] . "', countries)";
            }

            $objFilterRule = new SimpleQuery(
                'SELECT item_id FROM tl_metamodel_geoprotection WHERE attr_id = ? AND
                    ((mode = \'\') OR (mode = \'gp_show\' AND (' . implode(' OR ', $arrCountry) . ')) OR
                    (mode = \'gp_hide\' AND NOT (' . implode(' OR ', $arrCountry) . ')))',
                array($this->get('gp_attr_id')),
                'item_id'
            );

            $objFilter->addFilterRule($objFilterRule);

            return;
        }
        // No attribute found.
        $objFilter->addFilterRule(new StaticIdList(array()));
    }
}
