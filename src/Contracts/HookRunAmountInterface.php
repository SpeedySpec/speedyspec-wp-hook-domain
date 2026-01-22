<?php

namespace SpeedySpec\WP\Hook\Domain\Contracts;

interface HookRunAmountInterface
{
    public function getRunAmount(HookNameInterface $name): int;

    public function incrementRunAmount(HookNameInterface $name): void;
}
