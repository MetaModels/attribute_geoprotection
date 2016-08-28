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
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author      David Greminger <david.greminger@1up.io>
 * @author      David Maack <david.maack@arcor.de>
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright   2012-2016 The MetaModels team.
 * @license     https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Table\Attribute\GeoProtection;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use MetaModels\Attribute\GeoProtection\Helper;
use MetaModels\DcGeneral\Events\BaseSubscriber;

/**
 * Handle events for tl_metamodel_attribute.alias_fields.attr_id.
 */
class Subscriber extends BaseSubscriber
{

    /**
     * Boot the system in the backend.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function registerEventsInDispatcher()
    {
        $this
            ->addListener(
                GetPropertyOptionsEvent::NAME,
                array($this, 'getCountriesByContinent')
            );
    }

    /**
     * Retrieve all database table names.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public static function getCountriesByContinent(GetPropertyOptionsEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_attribute')
            || ($event->getPropertyName() !== 'geoprotection')
        ) {
            return;
        }

        // Get the current values.
        $arrValues = $event->getOptions();

        // Call the helper to get all data.
        $arrValues = Helper::getCountriesByContinent($arrValues);

        // Set the new options.
        $event->setOptions($arrValues);
    }
}
