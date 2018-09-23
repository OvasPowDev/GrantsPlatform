<?php

namespace AppBundle\Entity;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 * @Serializer\ExclusionPolicy("All")
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Contract
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
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @var bool|null
     * @ORM\Column(name="active", type="boolean", nullable=false)
     * @Gedmo\Versioned()
     */
    private $active;

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
     * @var int
     * @ORM\Column(name="used_users", type="integer", nullable=true)
     * @Gedmo\Versioned()
     */
    private $usedUsers;

    /**
     * @var int
     * @ORM\Column(name="grants_used", type="integer", nullable=true)
     * @Gedmo\Versioned()
     */
    private $grantsUsed;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Plan",
     *     inversedBy="contracts"
     * )
     * @ORM\JoinColumn(
     *     name="plan_id",
     *     referencedColumnName="id"
     * )
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Required()
     */
    private $plan;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Company",
     *     inversedBy="contracts"
     * )
     * @ORM\JoinColumn(
     *     name="company_id",
     *     referencedColumnName="id"
     * )
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Required()
     */
    private $company;

    /**
     * @var
     */
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Contract
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
     * Set createdBy.
     *
     * @param string|null $createdBy
     *
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Contract
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set plan.
     *
     * @param \AppBundle\Entity\Plan|null $plan
     *
     * @return Contract
     */
    public function setPlan(Plan $plan = null)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan.
     *
     * @return \AppBundle\Entity\Plan|null
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set company.
     *
     * @param \AppBundle\Entity\Company|null $company
     *
     * @return Contract
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return \AppBundle\Entity\Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set usedUsers.
     *
     * @param int|null $usedUsers
     *
     * @return Contract
     */
    public function setUsedUsers($usedUsers = null)
    {
        $this->usedUsers = $usedUsers;

        return $this;
    }

    /**
     * Get usedUsers.
     *
     * @return int|null
     */
    public function getUsedUsers()
    {
        return $this->usedUsers;
    }

    /**
     * Set grantsUsed.
     *
     * @param int|null $grantsUsed
     *
     * @return Contract
     */
    public function setGrantsUsed($grantsUsed = null)
    {
        $this->grantsUsed = $grantsUsed;

        return $this;
    }

    /**
     * Get grantsUsed.
     *
     * @return int|null
     */
    public function getGrantsUsed()
    {
        return $this->grantsUsed;
    }
}
