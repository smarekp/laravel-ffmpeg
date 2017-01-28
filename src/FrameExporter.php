<?php

namespace Smarekp\LaravelFFMpeg;

class FrameExporter extends MediaExporter
{
    protected $mustBeAccurate = false;

    protected $saveMethod = 'saveFrame';

    public function accurate()
    {
        $this->mustBeAccurate = true;

        return $this;
    }

    public function unaccurate()
    {
        $this->mustBeAccurate = false;

        return $this;
    }

    public function getAccuracy()
    {
        return $this->mustBeAccurate;
    }

    public function saveFrame(string $fullPath)
    {
        $this->media->save($fullPath, $this->getAccuracy());

        return $this;
    }
}
