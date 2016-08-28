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
 * @author     David Maack <david.maack@arcor.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Attribute\GeoProtection;

/**
 * Upgrade handler class that changes structural changes in the database.
 * This should rarely be necessary but sometimes we need it.
 */
class UpgradeHandler
{
    /**
     * Retrieve the database instance from Contao.
     *
     * @return \Database
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected static function DB()
    {
        return \Database::getInstance();
    }


    /**
     * Handle database upgrade for the gp_attr_id 0> attr_id field in tl_metamodel_filtersetting.
     *
     * Since the field gr_attr_id will be removed, the information should be stored in the field attr_id.
     * Only update attr_id if this field is empty
     *
     * @return void
     */
    protected static function upgradeFiterSettingAttrId()
    {
        $objDB = self::DB();
        if ($objDB->tableExists('tl_metamodel_filtersetting', null, true)
            && $objDB->fieldExists('gp_attr_id', 'tl_metamodel_filtersetting', true)
        ) {
            // update the field
            $objDB->execute(
                'UPDATE `tl_metamodel_filtersetting`
                 SET attr_id = gp_attr_id
                 WHERE type = "geoprotection"
                    AND attr_id = 0'
            );
        }
    }

    /**
     * Perform all upgrade steps.
     *
     * @return void
     */
    public static function update()
    {
        self::upgradeFiterSettingAttrId();
    }
}
