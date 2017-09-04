<?php
/**
 * Customer Value plugin for Craft CMS
 *
 * Log customer lifetime value (LTV) on a user, to be used in filtering
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   CustomerValue
 * @since     1.0.0
 */

namespace Craft;

class CustomerValuePlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init ()
    {
        parent::init();

        // Check if Commerce is installed
        // Init events
        craft()->on('commerce_orders.onOrderComplete', function (Event $event) {
            craft()->customerValue->onCompleteOrder($event->params['order']);
        });
    }

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
    public function getDescription ()
    {
        return Craft::t('Log customer lifetime value (LTV) on a user, to be used in filtering');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl ()
    {
        return 'https://github.com/sjelfull/customervalue/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl ()
    {
        return 'https://raw.githubusercontent.com/sjelfull/customervalue/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper ()
    {
        return 'Superbig';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl ()
    {
        return 'https://superbig.co';
    }

    /**
     * @return bool
     */
    public function hasCpSection ()
    {
        return false;
    }

    /**
     * @return array
     */
    protected function defineSettings ()
    {
        return array(
            'someSetting' => array( AttributeType::String, 'label' => 'Some Setting', 'default' => '' ),
        );
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml ()
    {
        return craft()->templates->render('customervalue/CustomerValue_Settings', array(
            'settings' => $this->getSettings()
        ));
    }

    /**
     * @param mixed $settings The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings ($settings)
    {
        // Modify $settings here...

        return $settings;
    }

}