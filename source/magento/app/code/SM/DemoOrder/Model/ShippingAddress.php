<?php
namespace SM\DemoOrder\Model;

use Magento\Framework\Api\AttributeValueFactory;
use SM\DemoOrder\Api\Data\ShippingAddressInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
class ShippingAddress extends AbstractExtensibleModel implements ShippingAddressInterface
{

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @inheritDoc
     */
    public function getFirstname()
    {
        return $this->getData(ShippingAddressInterface::FIRSTNAME);
    }

    /**
     * @inheritDoc
     */
    public function setFirstname($firstName)
    {
        return $this->setData(ShippingAddressInterface::FIRSTNAME, $firstName);
    }

    /**
     * @inheritDoc
     */
    public function getLastname()
    {
        return $this->getData(ShippingAddressInterface::LASTNAME);
    }

    /**
     * @inheritDoc
     */
    public function setLastname($lastName)
    {
        return $this->setData(ShippingAddressInterface::LASTNAME, $lastName);
    }

    /**
     * @inheritDoc
     */
    public function getStreet()
    {
        return $this->getData(ShippingAddressInterface::STREET);
    }

    /**
     * @inheritDoc
     */
    public function setStreet($street)
    {
        return $this->setData(ShippingAddressInterface::STREET, $street);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(ShippingAddressInterface::CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(ShippingAddressInterface::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function getCountryId()
    {
        return $this->getData(ShippingAddressInterface::COUNTRY_ID);

    }

    /**
     * @inheritDoc
     */
    public function setCountryId($countryId)
    {
        return $this->setData(ShippingAddressInterface::COUNTRY_ID, $countryId);
    }

    /**
     * @inheritDoc
     */
    public function getRegion()
    {
        return $this->getData(ShippingAddressInterface::REGION);

    }

    /**
     * @inheritDoc
     */
    public function setRegion($region)
    {
        return $this->setData(ShippingAddressInterface::REGION, $region);
    }

    /**
     * @inheritDoc
     */
    public function getPostcode()
    {
        return $this->getData(ShippingAddressInterface::POSTCODE);
    }

    /**
     * @inheritDoc
     */
    public function setPostcode($postCode)
    {
        return $this->setData(ShippingAddressInterface::POSTCODE, $postCode);
    }

    /**
     * @inheritDoc
     */
    public function getTelephone()
    {
        return $this->getData(ShippingAddressInterface::TELEPHONE);
    }

    /**
     * @inheritDoc
     */
    public function setTelephone($telephone)
    {
        return $this->setData(ShippingAddressInterface::TELEPHONE, $telephone);
    }

    /**
     * @inheritDoc
     */
    public function getFax()
    {
        return $this->getData(ShippingAddressInterface::FAX);
    }

    /**
     * @inheritDoc
     */
    public function setFax($fax)
    {
        return $this->setData(ShippingAddressInterface::FAX, $fax);
    }
}
