<?php

namespace Smarekp\LaravelFFMpeg;

use FFMpeg\Format\FormatInterface;

class MediaExporter
{
    protected $media;

    protected $disk;

    protected $format;

    protected $saveMethod = 'saveAudioOrVideo';

    public function __construct(Media $media)
    {
        $this->media = $media;

        $this->disk = $media->getFile()->getDisk();
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function inFormat(FormatInterface $format)
    {
        $this->format = $format;

        return $this;
    }

    protected function getDisk()
    {
        return $this->disk;
    }

    public function toDisk($diskOrName)
    {
        if ($diskOrName instanceof Disk) {
            $this->disk = $diskOrName;
        } else {
            $this->disk = Disk::fromName($diskOrName);
        }

        return $this;
    }

    public function save(/*string*/ $path)
    {
        $file = $this->getDisk()->newFile($path);

        $destinationPath = $this->getDestinationPathForSaving($file);

        $this->createDestinationPathForSaving($file);

        $this->{$this->saveMethod}($destinationPath);

        if (!$this->getDisk()->isLocal()) {
            $this->moveSavedFileToRemoteDisk($destinationPath, $file);
        }

        return $this->media;
    }

    protected function moveSavedFileToRemoteDisk($localSourcePath, File $fileOnRemoteDisk)
    {
        return $fileOnRemoteDisk->put($localSourcePath) && @unlink($localSourcePath);
    }

    private function getDestinationPathForSaving(File $file)
    {
        if (!$file->getDisk()->isLocal()) {
            $tempName = tempnam(sys_get_temp_dir(), 'laravel-ffmpeg');

            return $tempName . '.' . $file->getExtension();
        }

        return $file->getFullPath();
    }

    private function createDestinationPathForSaving(File $file)
    {
        if (!$file->getDisk()->isLocal()) {
            return false;
        }

        $directory = pathinfo($file->getPath())['dirname'];

        return $file->getDisk()->createDirectory($directory);
    }

    private function saveAudioOrVideo(/*string*/ $fullPath)
    {
        $this->media->save($this->getFormat(), $fullPath);

        return $this;
    }
}
