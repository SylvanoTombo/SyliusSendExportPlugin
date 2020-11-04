<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\Transformer\Handler;

use Boldy\SyliusExportPlugin\Transformer\Handler;

final class ArrayToStringHandler extends Handler
{

    protected function process($key, $value)
    {
        return \implode('|', $value);
    }

    protected function allows($key, $value): bool
    {
        return \is_array($value);
    }
}
