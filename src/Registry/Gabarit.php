<?php


namespace Boldy\SyliusExportPlugin\Registry;


use Boldy\SyliusExportPlugin\Exporter\ExporterInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class Gabarit implements GabaritInterface
{
    /**
     * @var array|null
     */
    private $headers;
    /**
     * @var array|null
     */
    private $resourceKeys;

    private $exporters = [];

    /**
     * Gabarit constructor.
     * @param array|null $headers
     * @param array|null $resourceKeys
     */
    public function __construct(?array $headers, ?array $resourceKeys)
    {
        $this->headers = $headers;
        $this->resourceKeys = $resourceKeys;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getResourceKeys(): array
    {
        return $this->resourceKeys;
    }

    public function getExporter(string $format): ExporterInterface
    {
        return $this->exporters[$format];
    }

    public function addExporter(string $format, $exporter)
    {
        $this->exporters[$format] = $exporter;

        return $this;
    }
}