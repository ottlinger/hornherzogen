<?php

namespace hornherzogen;


/**
 * Class Applicant
 * contains information about an applicant that is used to persist a certain state in the database.
 *
 * @package hornherzogen
 */
class Applicant
{
    // set mailed = when the mail was send
    // start of form data
    private $week;
    private $gender;
    private $firstname;
    private $lastname;
    private $fullName; // combinedName to avoid double registrations
    private $street;
    private $houseNumber;
    private $zipCode;
    private $city;
    private $country;
    private $email;
    private $dojo;
    private $dateOfBirth;
    private $grading;
    private $dateOfLastGrading;
    private $twaNumber;

    private $room; // which kind of room
    private $partnerOne;
    private $partnerTwo;

    private $foodCategory;
    private $flexible;

    private $remarks;
    // end of form data

    // DB-specific stuff
    private $persistenceId;

    // Admin-related stuff
    private $createdAt; // date when status was set to APPLIED
    private $confirmedAt; // date when status was set to CONFIRMED
    private $finalRoom; // reference to other table, final room at Herzogenhorn
    private $currentStatus; // name in other table
    private $mailedAt; // when the mail was sent and status changed to REGISTERED
    private $paymentRequestedAt; // when the mail to pay was sent and status changed to WAITING_FOR_PAYMENT
    private $paymentReceivedAt; // when the payment was received successfully and status changed to PAID
    private $bookedAt;// final confirmation is sent out and status changed to BOOKED


    /**
     * @return mixed
     */
    public function getWeek()
    {
        switch ($this->week) {
            case "week1":
                return 1;
            case "week2":
                return 2;
            default:
                return $this->week;
        }
    }

    /**
     * @param mixed $week
     * @return Applicant
     */
    public function setWeek($week)
    {
        $this->week = $week;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Applicant
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return Applicant
     */
    public function setFirstname($firstname)
    {
        $this->fullName = NULL;
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return Applicant
     */
    public function setLastname($lastname)
    {
        $this->fullName = NULL;
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        if (!isset($this->fullName)) {
            $this->setFullName(NULL);
        }

        return $this->fullName;
    }

    /**
     * Combines the name as firstname lastname salt if not empty
     * @param mixed $salt
     * @return Applicant
     */
    public function setFullName($salt)
    {
        $this->fullName = trim($this->getFirstname() . ' ' . $this->getLastname() . ' ' . $salt);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     * @return Applicant
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param mixed $houseNumber
     * @return Applicant
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     * @return Applicant
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Applicant
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Applicant
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Applicant
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDojo()
    {
        return $this->dojo;
    }

    /**
     * @param mixed $dojo
     * @return Applicant
     */
    public function setDojo($dojo)
    {
        $this->dojo = $dojo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     * @return Applicant
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrading()
    {
        return $this->grading;
    }

    /**
     * @param mixed $grading
     * @return Applicant
     */
    public function setGrading($grading)
    {
        $this->grading = $grading;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOfLastGrading()
    {
        return $this->dateOfLastGrading;
    }

    /**
     * @param mixed $dateOfLastGrading
     * @return Applicant
     */
    public function setDateOfLastGrading($dateOfLastGrading)
    {
        $this->dateOfLastGrading = $dateOfLastGrading;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwaNumber()
    {
        return $this->twaNumber;
    }

    /**
     * @param mixed $twaNumber
     * @return Applicant
     */
    public function setTwaNumber($twaNumber)
    {
        $this->twaNumber = $twaNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     * @return Applicant
     */
    public function setRoom($room)
    {
        $this->room = $room;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartnerOne()
    {
        return $this->partnerOne;
    }

    /**
     * @param mixed $partnerOne
     * @return Applicant
     */
    public function setPartnerOne($partnerOne)
    {
        $this->partnerOne = $partnerOne;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartnerTwo()
    {
        return $this->partnerTwo;
    }

    /**
     * @param mixed $partnerTwo
     * @return Applicant
     */
    public function setPartnerTwo($partnerTwo)
    {
        $this->partnerTwo = $partnerTwo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFoodCategory()
    {
        return $this->foodCategory;
    }

    /**
     * @param mixed $foodCategory
     * @return Applicant
     */
    public function setFoodCategory($foodCategory)
    {
        $this->foodCategory = $foodCategory;
        return $this;
    }

    /**
     * @return mixed true if flexible, false otherwise
     */
    public function getFlexible()
    {
        switch ($this->flexible) {
            case "yes":
                return true;
            default:
                return false;
        }


    }

    /**
     * @param mixed $flexible
     * @return Applicant
     */
    public function setFlexible($flexible)
    {
        $this->flexible = $flexible;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Sets remark field to the given value at a maximum length of 400.
     *
     * @param mixed $remarks
     * @return Applicant
     */
    public function setRemarks($remarks)
    {
        if (isset($remarks)) {
            $this->remarks = mb_substr(trim($remarks), 0, 400, 'UTF-8');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPersistenceId()
    {
        return $this->persistenceId;
    }

    /**
     * @param mixed $persistenceId
     * @return Applicant
     */
    public function setPersistenceId($persistenceId)
    {
        $this->persistenceId = $persistenceId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Applicant
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmedAt()
    {
        return $this->confirmedAt;
    }

    /**
     * @param mixed $confirmedAt
     * @return Applicant
     */
    public function setConfirmedAt($confirmedAt)
    {
        $this->confirmedAt = $confirmedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinalRoom()
    {
        return $this->finalRoom;
    }

    /**
     * @param mixed $finalRoom
     * @return Applicant
     */
    public function setFinalRoom($finalRoom)
    {
        $this->finalRoom = $finalRoom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param mixed $currentStatus
     * @return Applicant
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMailedAt()
    {
        return $this->mailedAt;
    }

    /**
     * @param mixed $mailedAt
     * @return Applicant
     */
    public function setMailedAt($mailedAt)
    {
        $this->mailedAt = $mailedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentRequestedAt()
    {
        return $this->paymentRequestedAt;
    }

    /**
     * @param mixed $paymentRequestedAt
     * @return Applicant
     */
    public function setPaymentRequestedAt($paymentRequestedAt)
    {
        $this->paymentRequestedAt = $paymentRequestedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentReceivedAt()
    {
        return $this->paymentReceivedAt;
    }

    /**
     * @param mixed $paymentReceivedAt
     * @return Applicant
     */
    public function setPaymentReceivedAt($paymentReceivedAt)
    {
        $this->paymentReceivedAt = $paymentReceivedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookedAt()
    {
        return $this->bookedAt;
    }

    /**
     * @param mixed $bookedAt
     * @return Applicant
     */
    public function setBookedAt($bookedAt)
    {
        $this->bookedAt = $bookedAt;
        return $this;
    }

    /*
     * Database structure:
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `week` varchar(50) COLLATE utf8_bin DEFAULT NULL,
      `gender` varchar(10) COLLATE utf8_bin DEFAULT NULL,
      `vorname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
      `nachname` varchar(200) COLLATE utf8_bin DEFAULT NULL,
      `combinedName` varchar(400) COLLATE utf8_bin DEFAULT NULL,
      `street` varchar(250) COLLATE utf8_bin DEFAULT NULL,
      `houseno` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `plz` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `city` varchar(250) COLLATE utf8_bin DEFAULT NULL,
      `country` varchar(250) COLLATE utf8_bin DEFAULT NULL,
      `email` varchar(250) COLLATE utf8_bin DEFAULT NULL,
      `dojo` varchar(256) COLLATE utf8_bin DEFAULT NULL,
      `birthdate` date DEFAULT NULL,
      `grad` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `gradsince` date DEFAULT NULL,
      `twano` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `room` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `together1` varchar(100) COLLATE utf8_bin DEFAULT NULL,
      `together2` varchar(100) COLLATE utf8_bin DEFAULT NULL,
      `essen` varchar(20) COLLATE utf8_bin DEFAULT NULL,
      `flexible` tinyint(1) DEFAULT NULL,
      `additionals` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `mailed` timestamp NULL,
      `verified` timestamp NULL,
      `paymentmailed` timestamp NULL,
      `paymentreceived` timestamp NULL,
      `booked` timestamp NULL,
      `cancelled` timestamp NULL,
      `status` varchar(50) COLLATE utf8_bin DEFAULT 'APPLIED',
    */

}
