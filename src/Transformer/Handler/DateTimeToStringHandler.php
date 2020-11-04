<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\Transformer\Handler;

use Boldy\SyliusExportPlugin\Transformer\Handler;

final class DateTimeToStringHandler extends Handler
{
    /** @var string */
    private $format;

    public function __construct(string $format = 'd/m/Y')
    {
        $this->format = $format;
    }

    protected function process($key, $value)
    {
        return $value->format($this->format);
    }

    protected function allows($key, $value): bool
    {
        return $value instanceof \DateTimeInterface;
    }
}
