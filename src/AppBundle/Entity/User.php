<?php
namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=50, nullable=true)
     */
    private $phoneNumber;
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255 , nullable=true)
     */
    protected $image;

    /**
     * @Assert\File(maxSize="600000000")
     */
    public $imageFile;

    public function __construct()
    {
        parent::__construct();
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadimageFile() {
        if (null !== $this->imageFile) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image = $filename . '.' . $this->imageFile->guessExtension();
        }
    }

    public function setFileimageFile(UploadedFile $file = null)
    {
        $this->imageFile = $file;
        // check if we have an old image image
        if (isset($this->image)) {
            // store the old name to delete after the update
            $this->temp = $this->image;
            $this->image = null;
        } else {
            $this->image = 'initial';
        }
    }
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadimageFile()
    {
        if (null === $this->imageFile)
        {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->imageFile->move($this->getUploadRootDirimageFile(), $this->image);

        unset($this->imageFile);
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUploadimageFile()
    {
        if(file_exists($this->getAbsoluteimageFile())) {
            if ($this->getUploadRootDirimageFile() . $this->image = $this->getimage()) {
                unlink($this->image);
            }
        }

    }

    public function getAbsoluteimageFile()
    {
        return null === $this->image ? null : $this->getUploadRootDirimageFile().'/'.$this->image;
    }

    public function getWebimageFile()
    {
        return null === $this->image ? null : $this->getUploadDirimageFile().'/'.$this->id.'/'.$this->image;
    }
    public function getUploadRootDirimageFile()
    {
        // the absolute directory image where uploaded documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDirimageFile().'/'.$this->id;
    }

    public function getUploadDirimageFile()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/user/image';

    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


}
