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

/**
 * This is the MetaModelAttribute class for handling text fields.
 *
 * @package    MetaModels
 * @subpackage AttributeGeoProtection
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class Helper
{
    /**
     * A list with all countries information.
     *
     * @var array|null
     */
    protected static $arrCountries;

    /**
     * A list with the full list of the countries.
     *
     * @var array|null
     */
    protected static $arrFullList;

    /**
     * Build up the list with all countries information and cache this information.
     *
     * Return all data from the cache.
     *
     * @return array|null
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public static function getCountriesList()
    {
        if (empty(self::$arrCountries)) {
            $countries = array();
            $arrTmp    = array();

            // Load the language files.
            \System::loadLanguageFile('countries');
            \System::loadLanguageFile('continents');

            // Include all files with name.
            require_once TL_ROOT . '/system/config/countries.php';
            require_once TL_ROOT . '/system/config/countriesByContinent.php';

            /** @var $countriesByContinent array */
            foreach ($countriesByContinent as $strConKey => $arrCountries) {
                // Add the main value.
                $strParentName = strlen($GLOBALS['TL_LANG']['CONTINENT'][$strConKey])
                    ? utf8_romanize($GLOBALS['TL_LANG']['CONTINENT'][$strConKey])
                    : $strConKey;

                // Add all countries.
                foreach (array_keys($arrCountries) as $key) {
                    $arrTmp[$key] = array(
                        'name'         => strlen($GLOBALS['TL_LANG']['CNT'][$key])
                            ? utf8_romanize($GLOBALS['TL_LANG']['CNT'][$key])
                            : $countries[$key],
                        'parent-name'  => $strParentName,
                        'parent-short' => $strConKey,
                    );
                }
            }

            self::$arrCountries = $arrTmp;
        }

        return self::$arrCountries;
    }

    /**
     * Get a list with all countries.
     *
     * @param array $arrValues A list with preset values.
     *
     * @return array|null The new list.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public static function getCountriesByContinent($arrValues = array())
    {
        $arrCountries = self::getCountriesList();
        $arrReturn    = array();

        if (empty($arrValues)) {
            $arrValues = array();
        }

        if (empty($arrValues)) {
            if (empty(self::$arrFullList)) {
                foreach ($arrCountries as $strShort => $arrInformation) {
                    $strName       = $arrInformation['name'];
                    $strParentName = $arrInformation['parent-name'];

                    $arrReturn[$strParentName][$strShort] = $strName;
                }
            } else {
                $arrReturn = self::$arrFullList;
            }
        } else {
            foreach ($arrValues as $strShort) {
                $arrInformation = $arrCountries[$strShort];
                $strName        = $arrInformation['name'];
                $strParentName  = $arrInformation['parent-name'];

                $arrReturn[$strParentName][$strShort] = $strName;
            }
        }

        // Add the other entry.
        if (in_array('xx', $arrValues) !== false) {
            $arrReturn[$GLOBALS['TL_LANG']['CONTINENT']['other']]['xx'] = strlen($GLOBALS['TL_LANG']['CNT']['xx'])
                ? $GLOBALS['TL_LANG']['CNT']['xx']
                : 'No Country';
        }

        // Sort the keys.
        ksort($arrReturn);

        // Sort the sub-values.
        foreach (array_keys($arrReturn) as $strConKey) {
            asort($arrReturn[$strConKey]);
        }

        return $arrReturn;
    }
}
