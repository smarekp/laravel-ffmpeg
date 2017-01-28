<?php

namespace Smarekp\LaravelFFMpeg;

class File
{
    protected $disk;

    protected $path;

    public function __construct(Disk $disk, /*string*/ $path)
    {
        $this->disk = $disk;
        $this->path = $path;
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getExtension()
    {
        return pathinfo($this->getPath())['extension'];
    }

    public function getFullPath()
    {
        return $this->getDisk()->getPath() . $this->getPath();
    }

    public function put(/*string*/ $localSourcePath)
    {
        $resource = fopen($localSourcePath, 'r');

        return $this->getDisk()->put($this->getPath(), $resource);
    }
}
