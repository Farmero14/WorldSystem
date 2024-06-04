<?php

declare(strict_types=1);

namespace Farmero\worldsystem;

use pocketmine\plugin\PluginBase;

use Farmero\worldsystem\command\WorldCommand;

class WorldSystem extends PluginBase {

    protected function onEnable(): void {
        $this->getServer()->getCommandMap()->register("WorldSystem", new WorldCommand($this));
    }
}