<?php

declare(strict_types=1);

namespace Farmero\worldsystem\generators;

use pocketmine\world\generator\normal\Normal;
use pocketmine\world\generator\Generator;

class PMGenerator extends Normal {

    public function __construct(int $seed, string $preset) {
        parent::__construct($seed, $preset);
    }

    public function getName(): string {
        return "normal";
    }
}