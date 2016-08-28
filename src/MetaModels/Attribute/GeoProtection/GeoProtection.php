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

namespace MetaModels\Attribute\GeoProtection;

use MetaModels\Attribute\BaseComplex;
use MetaModels\Render\Setting\ISimple;
use MetaModels\Render\Template;

/**
 * This is the MetaModelAttribute class for handling text fields.
 *
 * @package    MetaModels
 * @subpackage AttributeGeoProtection
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class GeoProtection extends BaseComplex
{
    /**
     * When rendered via a template, this returns the values to be stored in the template.
     *
     * @param Template $objTemplate Template object.
     * @param array    $arrRowData  Array with data.
     * @param ISimple  $objSettings Settings object.
     *
     * @return void
     */
    protected function prepareTemplate(Template $objTemplate, $arrRowData, $objSettings)
    {
        parent::prepareTemplate($objTemplate, $arrRowData, $objSettings);
        $objTemplate->value = $arrRowData[$this->getColName()][0];
        $objTemplate->raw   = $arrRowData[$this->getColName()][0];
    }


    /**
     * {@inheritdoc}
     */
    public function getAttributeSettingNames()
    {
        return array_merge(parent::getAttributeSettingNames(), array(
            'geoprotection',
        ));
    }


    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getFieldDefinition($arrOverrides = array())
    {
        // Load the language files.
        \Controller::loadLanguageFile('tl_metamodel_attribute');
        \Controller::loadLanguageFile('default');

        $arrFieldDef              = parent::getFieldDefinition($arrOverrides);
        $arrFieldDef['inputType'] = 'multiColumnWizard';
        $arrFieldDef['eval']      = array(
            'columnFields' => array(
                'gp_mode'      => array(
                    'inputType' => 'select',
                    'eval'      => array(
                        'style'              => 'width:180px',
                        'includeBlankOption' => true,
                        'columnPos'          => 'first',
                    ),
                    'options'   => array(
                        'gp_show' => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['gp_show'],
                        'gp_hide' => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['gp_hide'],
                    ),
                ),
                'gp_countries' => array(
                    'inputType' => 'checkbox',
                    'options'   => $this->getSelectedCountries(),
                    'eval'      => array(
                        'multiple'  => true,
                        'columnPos' => 'first',
                    ),
                ),
            ),
            'buttons'      => array(
                'copy'   => false,
                'delete' => false,
                'up'     => false,
                'down'   => false,
            ),
        );

        return $arrFieldDef;
    }


    /**
     * {@inheritdoc}
     *
     * @codingStandardsIgnoreStart
     */
    public function getFilterOptions($idList, $usedOnly, &$arrCount = null)
    {
        $arrReturn = array();

        return $arrReturn;
        // @codingStandardsIgnoreEnd
    }


    /**
     * {@inheritdoc}
     */
    public function getDataFor($arrIds)
    {
        $arrData = array();
        $sql     = 'SELECT * FROM tl_metamodel_geoprotection WHERE attr_id = ? AND item_id = ?';

        foreach ($arrIds as $id) {
            $objResult = \Database::getInstance()
                                  ->prepare($sql)
                                  ->limit(1)
                                  ->execute($this->get('id'), $id);

            if ($objResult->numRows > 0) {
                $arrData[$id] = array(
                    array(
                        'gp_mode'      => $objResult->mode,
                        'gp_countries' => explode(',', $objResult->countries),
                    ),
                );
            }
        }

        return $arrData;
    }


    /**
     * {@inheritdoc}
     */
    public function setDataFor($arrValues)
    {
        $sql = 'INSERT INTO tl_metamodel_geoprotection %s ON DUPLICATE KEY UPDATE countries = ?, mode = ?';

        foreach ($arrValues as $id => $value) {
            if (!is_array($value)) {
                continue;
            }

            $arrTmp = (is_array($value[0]['gp_countries'])) ? $value[0]['gp_countries'] : array();

            $arrData = array(
                'countries' => implode(',', $arrTmp),
                'mode'      => $arrTmp,
                'attr_id'   => $this->get('id'),
                'item_id'   => $id,
            );

            \Database::getInstance()
                     ->prepare($sql)
                     ->set($arrData)
                     ->execute(implode(',', $arrTmp), $value[0]['gp_mode']);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function unsetDataFor($arrIds)
    {
        // Return nothing.
    }

    /**
     * Returns an array with selected countries.
     *
     * @return array|null
     */
    public function getSelectedCountries()
    {
        $objValue = \Database::getInstance()
                             ->prepare('SELECT geoprotection FROM tl_metamodel_attribute WHERE id = ?')
                             ->limit(1)
                             ->execute($this->get('id'));

        return Helper::getCountriesByContinent(deserialize($objValue->geoprotection));
    }
}
