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

    public function with($torrentPath)
    {
        if (!file_exists($torrentPath)) {
            throw new \Exception(sprintf('Failed to open "%s" because file does not exist', $torrentPath));
        }

        $this->torrentPath = $torrentPath;

        $this->process = new Process(sprintf('transmission-show %s', $this->torrentPath));
        $this->process->run();

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
        $match = [];
        preg_match(sprintf('#%s: (.*)#m', $type), $this->process->getOutput(), $match);

        if (empty($match[1])) {
            return null;
        }

        return (string) $match[1];
    }

    public function getTracker()
    {
        $trackers = [];
        preg_match_all('/udp:\/\/(.*)/m', $this->process->getOutput(), $trackers);

        if (empty($trackers[1])) {
            return [];
        }

        return $trackers;
    }

    public function getFiles()
    {
        $compactFile = explode("FILES", $this->process->getOutput());

        if (empty($compactFile[1])) {
            return [];
        }

        return [];
        /*
         * Get file Info Pattern
         * $files = explode("\n", trim($compactFile[1]));
         * (.*) \(((.*) ([A-Za-z]{2}))\)
         */
    }
}