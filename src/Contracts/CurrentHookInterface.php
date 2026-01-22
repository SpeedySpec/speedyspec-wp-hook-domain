<?php

declare(strict_types=1);

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface CurrentHookInterface
{
    public function addHook(string $name): void;

    public function removeHook(): void;

    public function getCurrentHook(): ?HookNameInterface;

    public function hookTraceback(): array;

    public function addCallback(string $name): void;

    public function removeCallback(): void;

    public function getCurrentCallback(): ?string;

    public function callbackTraceback(): array;

    public function entireCallbackTraceback(): array;
}
