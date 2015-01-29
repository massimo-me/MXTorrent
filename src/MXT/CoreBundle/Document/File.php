<?php

namespace MXT\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="File")
 * @MongoDB\HasLifecycleCallbacks
 */
class File
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\String()
     */
    private $name;

    /**
     * @MongoDB\String()
     */
    private $size;

    /**
     * @MongoDB\ReferenceOne(targetDocument="MXT\CoreBundle\Document\Torrent")
     */
    private $torrent;

    /**
     * @MongoDB\Boolean
     */
    private $movie = false;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getTorrent()
    {
        return $this->torrent;
    }

    public function setTorrent(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * @return bool
     */
    public function isMovie()
    {
        return $this->movie;
    }

    /**
     * @param bool $movie
     */
    public function setMovie($movie)
    {
        $this->movie = $movie;
    }

}
