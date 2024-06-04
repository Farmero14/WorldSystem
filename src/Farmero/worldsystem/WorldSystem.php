<?php

declare(strict_types=1);

namespace Farmero\worldsystem;

use pocketmine\plugin\PluginBase;
use pocketmine\world\generator\GeneratorManager;

use Farmero\worldsystem\command\WorldCommand;
use Farmero\worldsystem\generators\VoidGenerator;
use Farmero\worldsystem\generators\FlatGenerator;
use Farmero\worldsystem\generators\PMGenerator;

class WorldSystem extends PluginBase {

    protected function onEnable(): void {
        $this->registerGenerators();
        $this->getServer()->getCommandMap()->register("WorldSystem", new WorldCommand($this));
    }

    private function registerGenerators(): void {
        GeneratorManager::getInstance()->addGenerator(VoidGenerator::class, "void", fn() => null);
        GeneratorManager::getInstance()->addGenerator(FlatGenerator::class, "farmero_flat", fn() => null);
        GeneratorManager::getInstance()->addGenerator(PMGenerator::class, "normal", fn() => null);
    }
}
