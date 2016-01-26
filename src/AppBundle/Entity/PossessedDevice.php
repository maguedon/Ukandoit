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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="DeviceType")
     * @ORM\JoinColumn(name="deviceType_id", referencedColumnName="id")
     */
    private $deviceType;


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
     * Set url
     *
     * @param string $url
     *
     * @return PossessedDevice
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
}
