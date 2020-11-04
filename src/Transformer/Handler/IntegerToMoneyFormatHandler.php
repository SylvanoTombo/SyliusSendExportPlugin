<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\Transformer\Handler;

use Boldy\SyliusExportPlugin\Transformer\Handler;

final class IntegerToMoneyFormatHandler extends Handler
{
    /** @var array */
    private $keys;

    /** @var string */
    private $format;

    /**
     * @param string[] $allowedKeys
     * @param string $format
     */
    public function __construct(array $allowedKeys, string $format = '%.2n')
    {
        $this->keys = $allowedKeys;
        $this->format = $format;
    }

    protected function process($key, $value): ?string
    {
        return money_format($this->format, $value / 100);
    }

    protected function allows($key, $value): bool
    {
        return is_int($value) && in_array($key, $this->keys);
    }
}
