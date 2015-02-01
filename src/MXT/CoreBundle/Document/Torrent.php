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
     * @MongoDB\String
     */
    private $fullTitle;

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

    /**
     * @MongoDB\String
     */
    private $image = null;

    private $link;

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

    public function getFullTitle()
    {
        return $this->fullTitle;
    }

    public function setFullTitle($fullTitle)
    {
        $this->fullTitle = $fullTitle;
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

    public function setFiles($files)
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

    public function hasFiles()
    {
        return $this->files->count() > 0;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }
}

