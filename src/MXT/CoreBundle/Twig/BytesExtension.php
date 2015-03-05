<?php

namespace MXT\CoreBundle\Twig;

class BytesExtension extends \Twig_Extension
{
    const KB = 1024;
    const MB = 1048576;
    const GB = 1073741824;

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('humanBytes', [
                    $this,
                    'humanBytesFilter'
                ]
            )
        ];
    }

    public function humanBytesFilter($bytes)
    {
        if ($bytes < self::KB) {
            return $bytes . ' B';
        } elseif ($bytes < self::MB) {
            return round($bytes / self::KB, 2) . ' KB';
        } elseif ($bytes < self::GB) {
            return round($bytes / self::MB, 2) . ' MB';
        } else {
            return round($bytes / self::GB, 2) . ' GB';
        }
    }

    public function getName()
    {
        return 'bytes_extensions';
    }
}