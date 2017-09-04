<?php
/**
 * Customer Value plugin for Craft CMS
 *
 * CustomerValue FieldType
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   CustomerValue
 * @since     1.0.0
 */

namespace Craft;

use Commerce\Extensions\CommerceTwigExtension;
use Commerce\Helpers\CommerceCurrencyHelper;

class CustomerValueFieldType extends BaseFieldType
{
    /**
     * @return mixed
     */
    public function getName ()
    {
        return Craft::t('Customer Value');
    }

    /**
     * @return mixed
     */
    public function defineContentAttribute ()
    {
        return [
            AttributeType::Number,
            'decimals' => 4,
            'default'  => 0
        ];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return string
     */
    public function getInputHtml ($name, $value)
    {
        if ( !$value )
            $value = 0;

        $id              = craft()->templates->formatInputId($name);
        $namespacedId    = craft()->templates->namespaceInputId($id);
        $primaryCurrency = craft()->commerce_paymentCurrencies->getPrimaryPaymentCurrency();
        $value           = craft()->numberFormatter->formatCurrency($value, $currency = (string)$primaryCurrency, $stripZeros = true);

        $variables = array(
            'id'          => $id,
            'name'        => $name,
            'namespaceId' => $namespacedId,
            'value'       => $value,
            'settings'    => $this->getSettings(),
        );

        return craft()->templates->render('customervalue/fields/CustomerValueFieldType.twig', $variables);
    }

    public function getSettingsHtml ()
    {
        return craft()->templates->render('customervalue/CustomerValue_Settings', [
            'settings' => $this->getSettings(),
        ]);
    }

    protected function defineSettings ()
    {
        return [
            'showField' => [ AttributeType::Bool, 'default' => false ],
        ];
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function prepValueFromPost ($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function prepValue ($value)
    {
        return $value;
    }
}