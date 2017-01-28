<?php

namespace Smarekp\LaravelFFMpeg;

use FFMpeg\FFMpeg as BaseFFMpeg;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Filesystem\Factory as Filesystems;
use Psr\Log\LoggerInterface;

class FFMpeg
{
    protected static $filesystems;

    protected $disk;

    protected $ffmpeg;

    public function __construct(Filesystems $filesystems, ConfigRepository $config, LoggerInterface $logger)
    {
        static::$filesystems = $filesystems;

        $ffmpegConfig = $config->get('laravel-ffmpeg');

        $this->ffmpeg = BaseFFMpeg::create($ffmpegConfig, $logger);
        $this->fromDisk(isset($ffmpegConfig['default_disk']) ? $ffmpegConfig['default_disk'] : $config->get('filesystems.default'));
    }

    public static function getFilesystems()
    {
        return static::$filesystems;
    }

    public function fromDisk(string $diskName)
    {
        $filesystem = static::getFilesystems()->disk($diskName);
        $this->disk = new Disk($filesystem);

        return $this;
    }

    public function open($path)
    {
        $file = $this->disk->newFile($path);

        $ffmpegMedia = $this->ffmpeg->open($file->getFullPath());

        return new Media($file, $ffmpegMedia);
    }
}
