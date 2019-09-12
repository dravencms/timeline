<?php
namespace Dravencms\Model\Timeline\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;

/**
 * Class Group
 * @package App\Model\Timeline\Entities
 * @ORM\Entity
 * @ORM\Table(name="timelineGroup")
 */
class Group
{
    use Nette\SmartObject;
    use Identifier;
    use TimestampableEntity;

    /**
     * @var string
     * @ORM\Column(type="string",length=255,nullable=false,unique=true)
     */
    private $name;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isShowName;

    /**
     * @var ArrayCollection|Timeline[]
     * @ORM\OneToMany(targetEntity="Timeline", mappedBy="group",cascade={"persist"})
     */
    private $timelines;

    /**
     * Group constructor.
     * @param $name
     * @param bool $isShowName
     */
    public function __construct($name, $isShowName = false)
    {
        $this->name = $name;
        $this->isShowName = $isShowName;

        $this->timelines = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isIsShowName()
    {
        return $this->isShowName;
    }

    /**
     * @return ArrayCollection|Timeline[]
     */
    public function getTimelines()
    {
        return $this->timelines;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param boolean $isShowName
     */
    public function setIsShowName($isShowName)
    {
        $this->isShowName = $isShowName;
    }
}

