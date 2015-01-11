<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Document\Torrent.php
 *
 */
namespace MXT\CoreBundle\Document;

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
     * @MongoDB\Date
     */
    private $date;

    /**
     * @MongoDB\String
     */
    private $torrentLink;

    /**
     * @MongoDB\Int
     */
    private $files;

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

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files)
    {
        $this->files = $files;
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
}

