<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRepository")
 * @Serializer\ExclusionPolicy("All")
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Plan
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
     * @var string
     * @ORM\Column(name="name", type="string", length=150)
     * @Assert\NotBlank()
     * @Gedmo\Versioned()
     * @Assert\Length(min="2", max="150")
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Gedmo\Versioned()
     */
    private $description;

    /**
     * @var bool|null
     * @ORM\Column(name="active", type="boolean", nullable=true)
     * @Gedmo\Versioned()
     */
    private $active;

    /**
     * @var int
     * @ORM\Column(name="quantity_user", type="integer")
     * @Assert\NotBlank()
     * @Gedmo\Versioned()
     */
    private $quantityUser;

    /**
     * @var int|null
     * @ORM\Column(name="time", type="integer", nullable=true)
     * @Gedmo\Versioned()
     */
    private $time;

    /**
     * @var int
     * @ORM\Column(name="quantity_grant", type="integer")
     * @Assert\NotBlank()
     * @Gedmo\Versioned()
     */
    private $quantityGrant;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=180, nullable=true)
     * @Gedmo\Blameable(on="create")
     */
    private $createdBy;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_by", type="string", length=180, nullable=true)
     * @Gedmo\Blameable(on="update")
     */
    private $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Expose()
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Serializer\Expose()
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="created_from_ip", type="string", length=45, nullable=true)
     * @Gedmo\IpTraceable(on="create")
     */
    private $createdFromIp;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_from_ip", type="string", length=45, nullable=true)
     * @Gedmo\IpTraceable(on="update")
     */
    private $updatedFromIp;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return plan
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return plan
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return plan
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set quantityUser.
     *
     * @param int $quantityUser
     *
     * @return plan
     */
    public function setQuantityUser($quantityUser)
    {
        $this->quantityUser = $quantityUser;

        return $this;
    }

    /**
     * Get quantityUser.
     *
     * @return int
     */
    public function getQuantityUser()
    {
        return $this->quantityUser;
    }

    /**
     * Set time.
     *
     * @param int|null $time
     *
     * @return plan
     */
    public function setTime($time = null)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time.
     *
     * @return int|null
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set quantityGrant.
     *
     * @param int $quantityGrant
     *
     * @return plan
     */
    public function setQuantityGrant($quantityGrant)
    {
        $this->quantityGrant = $quantityGrant;

        return $this;
    }

    /**
     * Get quantityGrant.
     *
     * @return int
     */
    public function getQuantityGrant()
    {
        return $this->quantityGrant;
    }

    /**
     * Set createdBy.
     *
     * @param string|null $createdBy
     *
     * @return plan
     */
    public function setCreatedBy($createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return string|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy.
     *
     * @param string|null $updatedBy
     *
     * @return plan
     */
    public function setUpdatedBy($updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy.
     *
     * @return string|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return plan
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return plan
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return plan
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set createdFromIp.
     *
     * @param string|null $createdFromIp
     *
     * @return plan
     */
    public function setCreatedFromIp($createdFromIp = null)
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    /**
     * Get createdFromIp.
     *
     * @return string|null
     */
    public function getCreatedFromIp()
    {
        return $this->createdFromIp;
    }

    /**
     * Set updatedFromIp.
     *
     * @param string|null $updatedFromIp
     *
     * @return plan
     */
    public function setUpdatedFromIp($updatedFromIp = null)
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    /**
     * Get updatedFromIp.
     *
     * @return string|null
     */
    public function getUpdatedFromIp()
    {
        return $this->updatedFromIp;
    }
}
