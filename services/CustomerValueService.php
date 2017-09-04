<?php
/**
 * Customer Value plugin for Craft CMS
 *
 * CustomerValue Service
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   CustomerValue
 * @since     1.0.0
 */

namespace Craft;

class CustomerValueService extends BaseApplicationComponent
{
    /**
     */
    public function onCompleteOrder (Commerce_OrderModel $order)
    {
        $customer = $order->getCustomer();
        $user     = $customer->getUser();

        if ( $user ) {
            // Find LTV field if any
            // Calculate total for user
            $orders = $customer->getOrders();
            $total  = (float)0;

            foreach ($orders as $customerOrder) {
                if ( $customerOrder->isCompleted ) {
                    $total = $total + $customerOrder->totalPaid;
                }
            }

            CustomerValuePlugin::log(Craft::t('Total paid for customer {id}: ${total}', [ 'id' => $user->id, 'total' => $total, ]), LogLevel::Info, $force = true);

            $fieldLayout = $user->getFieldLayout();

            if ( $fieldLayout ) {
                $fields = [ ];

                // Make sure $this->_content is set
                $user->getContent();

                foreach ($fieldLayout->getFields() as $fieldLayoutField) {
                    $field = $fieldLayoutField->getField();

                    CustomerValuePlugin::log(Craft::t('Field type: {type}', [ 'type' => get_class($field->getFieldType()), ]), LogLevel::Info, $force = true);

                    if ( $field && $fieldType = $field->getFieldType() ) {

                        if ( $fieldType instanceof CustomerValueFieldType ) {
                            $handle = $field->handle;

                            $fields[ $handle ] = $total;
                        }
                    }
                }

                $user->getContent()->setAttributes($fields);
                //$user->setContentFromPost($fields);

                craft()->users->saveUser($user);

                if ( $user->hasErrors() ) {
                    CustomerValuePlugin::log(Craft::t('Failed to save customer value for user: {errors}', [ 'errors' => json_encode($user->getErrors()), ]), LogLevel::Error);
                }
            }
        }
    }

}