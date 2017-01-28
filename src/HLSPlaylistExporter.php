<?php

namespace Smarekp\LaravelFFMpeg;

use FFMpeg\Format\VideoInterface;
use Smarekp\LaravelFFMpeg\SegmentedExporter;

class HLSPlaylistExporter extends MediaExporter
{
    protected $formats = [];

    protected $playlistPath;

    protected $segmentLength = 10;

    protected $segmentedExporters;

    protected $saveMethod = 'savePlaylist';

    public function addFormat(VideoInterface $format)
    {
        $this->formats[] = $format;

        return $this;
    }

    public function getFormatsSorted()
    {
        usort($this->formats, function ($formatA, $formatB) {
            // Replaced spaceship operator with ternary equivalent.
            // See: https://wiki.php.net/rfc/combined-comparison-operator#usefulness
            $a = $formatA->getKiloBitrate();
            $b = $formatB->getKiloBitrate();
            return ($a < $b) ? -1 : (($a > $b) ? 1 : 0);
        });

        return $this->formats;
    }

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

    protected function getSegmentedExporterFromFormat(VideoInterface $format)
    {
        $media = clone $this->media;

        return (new SegmentedExporter($media))
            ->inFormat($format)
            ->setPlaylistPath($this->playlistPath)
            ->setSegmentLength($this->segmentLength);
    }

    public function getSegmentedExporters()
        if ($this->segmentedExporters) {
            return $this->segmentedExporters;
        }

        return $this->segmentedExporters = array_map(function ($format) {
            return $this->getSegmentedExporterFromFormat($format);
        }, $this->getFormatsSorted());
    }

    protected function exportStreams()
    {
        foreach ($this->getSegmentedExporters() as $segmentedExporter) {
            $segmentedExporter->saveStream($this->playlistPath);
        }
    }

    protected function getMasterPlaylistContents()
    {
        $lines = ['#EXTM3U'];

        foreach ($this->getSegmentedExporters() as $segmentedExporter) {
            $bitrate = $segmentedExporter->getFormat()->getKiloBitrate() * 1000;

            $lines[] = '#EXT-X-STREAM-INF:BANDWIDTH=' . $bitrate;
            $lines[] = $segmentedExporter->getPlaylistFilename();
        }

        return implode(PHP_EOL, $lines);
    }

    public function savePlaylist(string $playlistPath)
    {
        $this->setPlaylistPath($playlistPath);
        $this->exportStreams();

        file_put_contents(
            $playlistPath,
            $this->getMasterPlaylistContents()
        );

        return $this;
    }
}
