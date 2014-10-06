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

namespace MetaModels\Attribute\Geoprotection;

use MetaModels\Attribute\BaseComplex;
use MetaModels\Render\Template;

/**
 * This is the MetaModelAttribute class for handling text fields.
 *
 * @package       MetaModels
 * @subpackage    AttributeGeoProtection
 * @author        Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class GeoProtection extends BaseComplex
{
	/**
	 * when rendered via a template, this returns the values to be stored in the template.
	 */
	protected function prepareTemplate(Template $objTemplate, $arrRowData, $objSettings = null)
	{
		parent::prepareTemplate($objTemplate, $arrRowData, $objSettings);
		$objTemplate->value = $arrRowData[$this->getColName()][0];
		$objTemplate->raw   = $arrRowData[$this->getColName()][0];
	}

	/////////////////////////////////////////////////////////////////
	// interface IMetaModelAttribute
	/////////////////////////////////////////////////////////////////


	public function getAttributeSettingNames()
	{
		return array_merge(parent::getAttributeSettingNames(), array(
			'geoprotection'
		));
	}

	public function getFieldDefinition($arrOverrides = array())
	{
		// Load the language files.
		\Controller::loadLanguageFile('tl_metamodel_attribute');
		\Controller::loadLanguageFile('default');

		$arrFieldDef              = parent::getFieldDefinition($arrOverrides);
		$arrFieldDef['inputType'] = 'multiColumnWizard';
		$arrFieldDef['eval']      = array(
			'columnFields' => array
			(
				'gp_mode'      => array
				(
					'inputType' => 'select',
					'eval'      => array
					(
						'style'              => 'width:180px',
						'includeBlankOption' => true,
						'columnPos'          => 'first'
					),
					'options'   => array
					(
						'gp_show' => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['gp_show'],
						'gp_hide' => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['gp_hide']
					),
				),
				'gp_countries' => array
				(
					'inputType' => 'checkbox',
					'options'   => $this->getSelectedCountries(),
					'eval'      => array
					(
						'multiple'  => true,
						'columnPos' => 'first'
					)
				),
			),
			'buttons'      => array
			(
				'copy'   => false,
				'delete' => false,
				'up'     => false,
				'down'   => false
			)
		);

		return $arrFieldDef;
	}

	public function getFilterOptions($arrIds, $usedOnly, &$arrCount = null)
	{
		$arrReturn = array();

		return $arrReturn;
	}

	/////////////////////////////////////////////////////////////////
	// interface IMetaModelAttributeComplex
	/////////////////////////////////////////////////////////////////

	public function getDataFor($arrIds)
	{
		$arrData = array();
		foreach ($arrIds as $id)
		{
			$objResult = \Database::getInstance()
				->prepare('SELECT * FROM tl_metamodel_geoprotection WHERE attr_id = ? AND item_id = ?')
				->limit(1)
				->execute($this->get('id'), $id);

			if ($objResult->numRows > 0)
			{
				$arrData[$id] = array
				(
					array
					(
						'gp_mode'      => $objResult->mode,
						'gp_countries' => explode(',', $objResult->countries)
					)
				);
			}
		}

		return $arrData;
	}

	public function setDataFor($arrValues)
	{
		foreach ($arrValues as $id => $value)
		{
			if (!is_array($value))
			{
				continue;
			}

			$arrTmp = (is_array($value[0]['gp_countries'])) ? $value[0]['gp_countries'] : array();

			$arrData = array
			(
				'countries' => implode(',', $arrTmp),
				'mode'      => $arrTmp,
				'attr_id'   => $this->get('id'),
				'item_id'   => $id
			);

			\Database::getInstance()
				->prepare('INSERT INTO tl_metamodel_geoprotection %s ON DUPLICATE KEY UPDATE countries = ?, mode = ?')
				->set($arrData)
				->execute(implode(',', $arrTmp), $value[0]['gp_mode']);
		}
	}

	public function unsetDataFor($arrIds)
	{
		// TODO: unset Data

	}

	public function getSelectedCountries()
	{
		$objValue = \Database::getInstance()
			->prepare('SELECT geoprotection FROM tl_metamodel_attribute WHERE id = ?')
			->limit(1)
			->execute($this->get('id'));

		return Helper::getCountriesByContinent(deserialize($objValue->geoprotection));

	}

}