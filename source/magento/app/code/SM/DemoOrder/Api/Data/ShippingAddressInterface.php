<?php
namespace SM\DemoOrder\Api\Data;

interface ShippingAddressInterface
{


    /**#@+
     * Constants defined for keys of  data array
     */
    const FIRSTNAME = 'firstname';

    const LASTNAME = 'lastname';

    const STREET = 'street';

    const CITY = 'city';

    const COUNTRY_ID = 'country_id';

    const REGION = 'region';

    const POSTCODE = 'postcode';

    const TELEPHONE = 'telephone';

    const FAX = 'fax';

    const ATTRIBUTES = [
        self::FIRSTNAME,
        self::LASTNAME,
        self::STREET,
        self::CITY,
        self::COUNTRY_ID,
        self::REGION,
        self::POSTCODE,
        self::TELEPHONE,
        self::FAX
    ];


    /**
     * @return string
     */
    public function getFirstname();

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname);

    /**
     * @return string
     */
    public function getLastname();

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname);

    /**
     * @return string
     */
    public function getStreet();

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet($street);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * @return string
     */
    public function getCountryId();

    /**
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion($region);

    /**
     * @return string
     */
    public function getPostcode();

    /**
     * @param string $postCode
     * @return $this
     */
    public function setPostcode($postCode);

    /**
     * @return string
     */
    public function getTelephone();

    /**
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * @return string
     */
    public function getFax();

    /**
     * @param string $fax
     * @return $this
     */
    public function setFax($fax);

}
