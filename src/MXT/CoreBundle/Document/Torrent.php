<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Document\Torrent.php
 *
 */
namespace MXT\CoreBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Documents\File;

/**
 * @MongoDB\Document
 */
class Torrent
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $title;

    /**
     * @MongoDB\Date
     */
    private $date;

    /**
     * @MongoDB\String
     */
    private $torrentLink;

    /**
     * @MongoDB\String
     */
    private $hash;

    /**
     * @MongoDB\String
     */
    private $size;

    /**
     * @MongoDB\Boolean
     */
    private $verified;

    /**
     * @MongoDB\ReferenceMany(targetDocument="MXT\CoreBundle\Document\File", inversedBy="torrent", cascade={"persist"})
     * @var null|ArrayCollection
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getTorrentLink()
    {
        return $this->torrentLink;
    }

    public function setTorrentLink($torrentLink)
    {
        $this->torrentLink = $torrentLink;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getVerified()
    {
        return (bool) $this->verified;
    }

    public function setVerified($verified)
    {
        $this->verified = (bool) $verified;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles(ArrayCollection $files)
    {
        $this->files = $files;
    }

    public function addFile(File $file)
    {
        $this->files[] = $file;
    }

    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }
}

