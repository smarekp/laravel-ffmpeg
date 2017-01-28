<?php

namespace Smarekp\LaravelFFMpeg;

use FFMpeg\Media\Frame as BaseFrame;

/**
 * @method BaseFrame save($pathfile, $accurate = false)
 */
class Frame extends Media
{
    public function export()
    {
        return new FrameExporter($this);
    }
}
