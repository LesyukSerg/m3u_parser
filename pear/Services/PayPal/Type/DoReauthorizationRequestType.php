<?php
/**
 * @package Services_PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'Services/PayPal/Type/AbstractRequestType.php';

/**
 * DoReauthorizationRequestType
 *
 * @package Services_PayPal
 */
class DoReauthorizationRequestType extends AbstractRequestType
{
    var $AuthorizationID;

    var $Amount;

    function DoReauthorizationRequestType()
    {
        parent::AbstractRequestType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
        $this->_elements = array_merge($this->_elements,
            array (
              'AuthorizationID' => 
              array (
                'required' => true,
                'type' => 'string',
                'namespace' => 'urn:ebay:api:PayPalAPI',
              ),
              'Amount' => 
              array (
                'required' => true,
                'type' => 'BasicAmountType',
                'namespace' => 'urn:ebay:api:PayPalAPI',
              ),
            ));
    }

    function getAuthorizationID()
    {
        return $this->AuthorizationID;
    }
    function setAuthorizationID($AuthorizationID, $charset = 'iso-8859-1')
    {
        $this->AuthorizationID = $AuthorizationID;
        $this->_elements['AuthorizationID']['charset'] = $charset;
    }
    function getAmount()
    {
        return $this->Amount;
    }
    function setAmount($Amount, $charset = 'iso-8859-1')
    {
        $this->Amount = $Amount;
        $this->_elements['Amount']['charset'] = $charset;
    }
}
