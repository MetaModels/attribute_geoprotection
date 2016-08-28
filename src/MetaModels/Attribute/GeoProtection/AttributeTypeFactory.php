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
 * @copyright   2012-2016 The MetaModels team.
 * @license     https://github.com/MetaModels/attribute_geoprotection/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Attribute\GeoProtection;

use MetaModels\Attribute\AbstractAttributeTypeFactory;
use MetaModels\Attribute\Events\CreateAttributeFactoryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AttributeTypeFactory.
 *
 * @package MetaModels\Attribute\GeoProtection
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CreateAttributeFactoryEvent::NAME => 'registerLegacyAttributeFactoryEvents',
        );
    }

    /**
     * Register all legacy factories and all types defined via the legacy array as a factory.
     *
     * @param CreateAttributeFactoryEvent $event The event.
     *
     * @return void
     */
    public static function registerLegacyAttributeFactoryEvents(CreateAttributeFactoryEvent $event)
    {
        $factory = $event->getFactory();
        $factory->addTypeFactory(new static());
    }

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->typeName  = 'geoprotection';
        $this->typeClass = 'MetaModels\Attribute\GeoProtection\GeoProtection';
    }
}
