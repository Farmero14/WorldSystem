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
        GeneratorManager::getInstance()->addGenerator("void", VoidGenerator::class);
        GeneratorManager::getInstance()->addGenerator("flat", FlatGenerator::class);
        GeneratorManager::getInstance()->addGenerator("normal", PMGenerator::class);
    }
}
