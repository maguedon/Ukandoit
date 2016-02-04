<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PossessedDevice
 *
 * @ORM\Table(name="possessed_device")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PossessedDeviceRepository")
 */
class PossessedDevice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="possessedDevices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_jawbone", type="string", length=255, nullable=true)
     */
    private $accessTokenJawbone;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id_withings", type="string", length=255, nullable=true)
     */
    private $userIdWithings;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_key_withings", type="string", length=255, nullable=true)
     */
    private $accessTokenKeyWithings;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_secret_withings", type="string", length=255, nullable=true)
     */
    private $accessTokenSecretWithings;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token_google", type="string", length=255, nullable=true)
     */
    private $accessTokenGoogle;

    /**
     * @ORM\ManyToOne(targetEntity="DeviceType")
     * @ORM\JoinColumn(name="deviceType_id", referencedColumnName="id")
     */
    private $deviceType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;


    function __construct(){
        $this->creationDate = new \DateTime();
        $this->accessTokenJawbone = null;
        $this->userIdWithings = null;
        $this->accessTokenKeyWithings = null;
        $this->accessTokenSecretWithings = null;
        $this->url = null;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return PossessedDevice
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return PossessedDevice
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set deviceType
     *
     * @param \AppBundle\Entity\DeviceType $deviceType
     *
     * @return PossessedDevice
     */
    public function setDeviceType(\AppBundle\Entity\DeviceType $deviceType = null)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return \AppBundle\Entity\DeviceType
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set accessTokenJawbone
     *
     * @param string $accessTokenJawbone
     *
     * @return PossessedDevice
     */
    public function setAccessTokenJawbone($accessTokenJawbone)
    {
        $this->accessTokenJawbone = $accessTokenJawbone;

        return $this;
    }

    /**
     * Get accessTokenJawbone
     *
     * @return string
     */
    public function getAccessTokenJawbone()
    {
        return $this->accessTokenJawbone;
    }

    /**
     * Set userIdWithings
     *
     * @param string $userIdWithings
     *
     * @return PossessedDevice
     */
    public function setUserIdWithings($userIdWithings)
    {
        $this->userIdWithings = $userIdWithings;

        return $this;
    }

    /**
     * Get userIdWithings
     *
     * @return string
     */
    public function getUserIdWithings()
    {
        return $this->userIdWithings;
    }

    /**
     * Set accessTokenKeyWithings
     *
     * @param string $accessTokenKeyWithings
     *
     * @return PossessedDevice
     */
    public function setAccessTokenKeyWithings($accessTokenKeyWithings)
    {
        $this->accessTokenKeyWithings = $accessTokenKeyWithings;

        return $this;
    }

    /**
     * Get accessTokenKeyWithings
     *
     * @return string
     */
    public function getAccessTokenKeyWithings()
    {
        return $this->accessTokenKeyWithings;
    }

    /**
     * Set accessTokenSecretWithings
     *
     * @param string $accessTokenSecretWithings
     *
     * @return PossessedDevice
     */
    public function setAccessTokenSecretWithings($accessTokenSecretWithings)
    {
        $this->accessTokenSecretWithings = $accessTokenSecretWithings;

        return $this;
    }

    /**
     * Get accessTokenSecretWithings
     *
     * @return string
     */
    public function getAccessTokenSecretWithings()
    {
        return $this->accessTokenSecretWithings;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return PossessedDevice
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return PossessedDevice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set accessTokenGoogle
     *
     * @param string $accessTokenGoogle
     * @return PossessedDevice
     */
    public function setAccessTokenGoogle($accessTokenGoogle)
    {
        $this->accessTokenGoogle = $accessTokenGoogle;

        return $this;
    }

    /**
     * Get accessTokenGoogle
     *
     * @return string 
     */
    public function getAccessTokenGoogle()
    {
        return $this->accessTokenGoogle;
    }
}
