<?php


namespace Boldy\SyliusExportPlugin\Transformer\Handler;


use Boldy\SyliusExportPlugin\Transformer\Handler;

class NullToZeroValueHandler extends Handler
{
    /**
     * @var array
     */
    private $allowedKeys;

    /**
     * NullToZeroValueHandler constructor.
     * @param array $allowedKeys
     */
    public function __construct(array $allowedKeys)
    {
        $this->allowedKeys = $allowedKeys;
    }

    protected function process($key, $value)
    {
        return 0;
    }

    protected function allows($key, $value): bool
    {
        return (!isset($value) || empty($value)) && in_array($key, $this->allowedKeys);
    }
}