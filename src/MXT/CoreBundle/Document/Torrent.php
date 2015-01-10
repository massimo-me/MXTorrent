<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Document\Torrent.php
 *
 */

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document
 */
class Torrent
{
    /**
     * @Mongo\Id
     */
    private $id;

    /**
     * @Mongo\String
     */
    private $title;

    /**
     * @Mongo\Date
     */
    private $date;

    /**
     * @Mongo\String
     */
    private $torrentLink;

    /**
     * @Mongo\Int
     */
    private $files;

    /**
     * @Mongo\String
     */
    private $hash;

    /**
     * @Mongo\String
     */
    private $size;

    /**
     * @Mongo\Boolean
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

