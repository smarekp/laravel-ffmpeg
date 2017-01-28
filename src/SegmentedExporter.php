<?php

namespace Smarekp\LaravelFFMpeg;

class SegmentedExporter extends MediaExporter
{
    protected $filter;

    protected $playlistPath;

    protected $segmentLength = 10;

    protected $saveMethod = 'saveStream';

    public function setPlaylistPath(string $playlistPath)
    {
        $this->playlistPath = $playlistPath;

        return $this;
    }

    public function setSegmentLength(int $segmentLength)
    {
        $this->segmentLength = $segmentLength;

        return $this;
    }

    public function getFilter()
    {
        if ($this->filter) {
            return $this->filter;
        }

        return $this->filter = new SegmentedFilter(
            $this->getPlaylistFullPath(),
            $this->segmentLength
        );
    }

    public function saveStream(string $playlistPath)
    {
        $this->setPlaylistPath($playlistPath);

        $this->media->addFilter(
            $this->getFilter()
        );

        $this->media->save(
            $this->getFormat(),
            $this->getSegmentFullPath()
        );

        return $this;
    }

    public function getPlaylistFullPath()
    {
        return implode(DIRECTORY_SEPARATOR, [
            pathinfo($this->playlistPath, PATHINFO_DIRNAME),
            $this->getPlaylistFilename(),
        ]);
    }

    public function getSegmentFullPath()
    {
        return implode(DIRECTORY_SEPARATOR, [
            pathinfo($this->playlistPath, PATHINFO_DIRNAME),
            $this->getSegmentFilename(),
        ]);
    }

    public function getPlaylistPath()
    {
        return $this->playlistPath;
    }

    public function getPlaylistName()
    {
        return pathinfo($this->getPlaylistPath(), PATHINFO_FILENAME);
    }

    public function getPlaylistFilename()
    {
        return $this->getFormattedFilename('.m3u8');
    }

    public function getSegmentFilename()
    {
        return $this->getFormattedFilename('_%05d.ts');
    }

    protected function getFormattedFilename(string $suffix = '')
    {
        return implode([
            $this->getPlaylistName(),
            '_',
            $this->getFormat()->getKiloBitrate(),
        ]) . $suffix;
    }
}
