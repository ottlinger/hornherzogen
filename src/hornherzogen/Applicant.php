<?php
declare(strict_types = 1);
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
    private $grading;
    private $dateOfLastGrading;
    private $twaNumber;
    private $language; // language used when submitting the form

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
    private $cancelledAt; // data at which an applicant cancelled his/her booking

    // TECHNICAL attributes
    protected $formHelper;

    function __construct()
    {
        $this->formHelper = new FormHelper();
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        switch ($this->week) {
            case "week1":
            case "1":
                return 1;
            case "week2":
            case "2":
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
            case "1":
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
        $this->remarks = $this->formHelper->trimAndCutAfter($remarks, 400);
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

    /**
     * @return mixed
     */
    public function getCancelledAt()
    {
        return $this->cancelledAt;
    }

    /**
     * @param mixed $cancelledAt
     * @return Applicant
     */
    public function setCancelledAt($cancelledAt)
    {
        $this->cancelledAt = $cancelledAt;
        return $this;
    }

}
