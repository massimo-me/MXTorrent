<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\TransmissionBundle\Services\Process\TransmissionShow
 *
 */
namespace MXT\TransmissionBundle\Services\Process;

use Symfony\Component\Process\Process;

class TransmissionShow
{
    public static $TORRENT_GENERAL_INFO = [
        'Name',
        'Hash',
        'Created by',
        'Created on',
        'Comment',
        'Piece Count',
        'Piece Size',
        'Total Size',
        'Privacy'
    ];

    /**
     * @var String
     */
    private $torrentPath;

    /**
     * @var Process
     */
    private $process;

    public function setTorrentPath($torrentPath)
    {
        if (!file_exists($torrentPath)) {
            throw new \Exception(sprintf('Failed to open "%s" because file does not exist', $torrentPath));
        }

        $this->torrentPath = $torrentPath;
        $this->run();
    }

    private function run()
    {
        $this->process = new Process(sprintf('transmission-show %s', $this->torrentPath));
        $this->process->run();
    }

    public function getTorrentPath()
    {
        return $this->torrentPath;
    }

    public function with($torrentPath)
    {
        $this->setTorrentPath($torrentPath);

        return $this;
    }

    public function getInfo() {
        $generalInfo = [];
        foreach(static::$TORRENT_GENERAL_INFO as $info) {
            $generalInfo[$info] = $this->getGeneralInfo($info);
        }

        return $generalInfo;
    }

    private function getGeneralInfo($type)
    {
        return $this->match(sprintf('#%s:\s(.*)#m', $type), false);
    }

    public function getFiles()
    {
        return $this->match('/[ \t]{2,}(.*)\s\((.*)[ \t]{1}([A-Za-z]{2})\)/m');
    }

    private function match($expression, $matchAll = true)
    {
        $match = [];
        
        if ($matchAll) {
            preg_match_all($expression, $this->process->getOutput(), $match);
        }else {
            preg_match($expression, $this->process->getOutput(), $match);
        }

        if (empty($match[1])) {
            return [];
        }
        return $match[1];
    }
}