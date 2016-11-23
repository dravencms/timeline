<?php
namespace Dravencms\Model\Timeline\Entities;

use App\Model\File\Entities\StructureFile;
use App\Model\Tag\Entities\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Sortable\Sortable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;

/**
 * Class Timeline
 * @package App\Model\Timeline\Entities
 * @ORM\Entity
 * @ORM\Table(name="timelineTimeline", uniqueConstraints={@UniqueConstraint(name="name_unique", columns={"name", "group_id"})})
 */
class Timeline extends Nette\Object
{
    use Identifier;
    use TimestampableEntity;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=false)
     */
    private $text;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     * and it is not necessary because globally locale can be set in listener
     */
    private $locale;

    /**
     * @var StructureFile
     * @ORM\ManyToOne(targetEntity="\App\Model\File\Entities\StructureFile", inversedBy="articles")
     * @ORM\JoinColumn(name="structure_file_id", referencedColumnName="id")
     */
    private $structureFile;

    /**
     * @var Group
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="articles")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;
}

