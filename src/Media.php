<?php

namespace Smarekp\LaravelFFMpeg;

use Closure;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\MediaTypeInterface;

/**
 * @method mixed save(\FFMpeg\Format\FormatInterface $format, $outputPathfile)
 */
class Media
{
    protected $file;

    protected $media;

    public function __construct(File $file, MediaTypeInterface $media)
    {
        $this->file  = $file;
        $this->media = $media;
    }

    public function isFrame()
    {
        return $this instanceof Frame;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDurationInSeconds()
    {
        return $this->media->getStreams()->first()->get('duration');
    }

    public function export()
    {
        return new MediaExporter($this);
    }

    public function exportForHLS()
    {
        return new HLSPlaylistExporter($this);
    }

    public function getFrameFromString(string $timecode)
    {
        return $this->getFrameFromTimecode(
            TimeCode::fromString($timecode)
        );
    }

    public function getFrameFromSeconds(float $quantity)
    {
        return $this->getFrameFromTimecode(
            TimeCode::fromSeconds($quantity)
        );
    }

    public function getFrameFromTimecode(TimeCode $timecode)
    {
        $frame = $this->media->frame($timecode);

        return new Frame($this->getFile(), $frame);
    }

    public function addFilter()
    {
        $arguments = func_get_args();

        if (isset($arguments[0]) && $arguments[0] instanceof Closure) {
            call_user_func_array($arguments[0], [$this->media->filters()]);
        } else {
            call_user_func_array([$this->media, 'addFilter'], $arguments);
        }

        return $this;
    }

    protected function selfOrArgument($argument)
    {
        return ($argument === $this->media) ? $this : $argument;
    }

    public function __invoke()
    {
        return $this->media;
    }

    public function __clone()
    {
        if ($this->media) {
            $clonedFilters = clone $this->media->getFiltersCollection();

            $this->media = clone $this->media;

            $this->media->setFiltersCollection($clonedFilters);
        }
    }

    public function __call($method, $parameters)
    {
        return $this->selfOrArgument(
            call_user_func_array([$this->media, $method], $parameters)
        );
    }
}
