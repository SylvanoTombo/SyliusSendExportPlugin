<?php


namespace Boldy\SyliusExportPlugin\Transformer\Handler;


use Boldy\SyliusExportPlugin\Transformer\Handler;

class NegativeToPositiveFormatHandler extends Handler
{
    /**
     * @var array
     */
    private $allowedKeys;

    /**
     * NegativeToPositiveFormatHandler constructor.
     * @param array $allowedKeys
     */
    public function __construct(array $allowedKeys)
    {
        $this->allowedKeys = $allowedKeys;
    }

    protected function process($key, $value)
    {
        return abs($value);
    }

    protected function allows($key, $value): bool
    {
        return is_numeric($value) && in_array($key, $this->allowedKeys) && $value < 0;
    }
}