<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="invoices")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\InvoiceRepository")
 * @ORM\HasLifecycleCallbacks
*/
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var PurchaseRequisition
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", inversedBy="invoice")
     * @ORM\JoinColumn(name="purchase_requisitions")
     */
    private $purchaseRequisition;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ReceivedItem", mappedBy="invoice", cascade={"persist", "remove"})
     */
    private $receivedItem;

    /**
     * @ORM\Column(name="comments", type="string", length=255)
     * @Assert\NotBlank
     */
    public $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_numbers", type="string", length=255, nullable=true)
     */
    private $invoiceNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ORM\Column(name="calan", type="string", length=255, nullable=true)
     */
    public $calan;

    /**
     * @Assert\File(maxSize="5M")
     */
    public $file;

    /**
     * @Assert\File(maxSize="5M")
     */
    public $fileCalan;

    public $temp;
    public $tempCalan;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReceivedItem()
    {
        return $this->receivedItem;
    }

    /**
     * Set invoiceNumber
     *
     * @param integer $invoiceNumber
     * @return Invoice
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return integer
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set purchaseRequisition
     *
     * @param integer $purchaseRequisition
     * @return Invoice
     */
    public function setPurchaseRequisition($purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;

        return $this;
    }

    /**
     * Get purchaseRequisition
     *
     * @return integer
     */
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/file/invoice';
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir() . '/' . $this->path;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadCalan()
    {
        if (null !== $this->getFileCalan()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->calan = $filename . '.' . $this->getFileCalan()->guessExtension();
        }
    }

    /**
     * Get fileCalan.
     *
     * @return UploadedFile
     */
    public function getFileCalan()
    {
        return $this->fileCalan;
    }

    /**
     * Sets fileCalan.
     *
     * @param UploadedFile $fileCalan
     */
    public function setFileCalan(UploadedFile $fileCalan = null)
    {
        $this->fileCalan = $fileCalan;
        // check if we have an old image path
        if (isset($this->calan)) {
            // store the old name to delete after the update
            $this->$tempCalan = $this->calan;
            $this->calan = null;
        } else {
            $this->calan = 'initial';
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadCalan()
    {
        if (null === $this->getFileCalan()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileCalan()->move($this->getUploadRootDirCalan(), $this->calan);

        // check if we have an old image
        if (isset($this->tempCalan)) {
            // delete the old image
            unlink($this->getUploadRootDirCalan() . '/' . $this->tempCalan);
            // clear the temp image path
            $this->tempCalan = null;
        }
        $this->fileCalan = null;
    }

    protected function getUploadRootDirCalan()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDirCalan();
    }

    protected function getUploadDirCalan()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/file/calan';
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadCalan()
    {
        if ($fileCalan = $this->getAbsolutePathCalan()) {
            unlink($fileCalan);
        }
    }

    public function getAbsolutePathCalan()
    {
        return null === $this->calan
            ? null
            : $this->getUploadRootDirCalan() . '/' . $this->calan;
    }

    public function getWebPathCalan()
    {
        return null === $this->calan
            ? null
            : $this->getUploadDirCalan() . '/' . $this->calan;
    }
}