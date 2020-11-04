<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\Transformer;

abstract class Handler implements HandlerInterface
{
    /** @var HandlerInterface */
    private $successor;

    final public function setSuccessor(HandlerInterface $handler): void
    {
        if ($this->successor === null) {
            $this->successor = $handler;

            return;
        }

        $this->successor->setSuccessor($handler);
    }

    final public function handle($key, $value)
    {
        $response = $this->allows($key, $value) ? $this->process($key, $value) : $value;

        if ($this->successor !== null) {
            $response = $this->successor->handle($key, $response);
        }

        return $response;
    }

    /**
     * Process the data. Return null to send to following handler.
     *
     * @param $key
     * @param $value
     * @return mixed|null
     */
    abstract protected function process($key, $value);

    /**
     * Will define whether this request will be handled by this handler (e.g. check on object type)
     *
     * @param $key
     * @param $value
     * @return bool
     */
    abstract protected function allows($key, $value): bool;
}
