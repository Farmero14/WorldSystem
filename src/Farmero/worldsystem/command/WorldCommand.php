<?php

declare(strict_types=1);

namespace Farmero\worldsystem\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\WorldCreationOptions;

use Farmero\worldsystem\WorldSystem;

use Farmero\worldsystem\generators\VoidGenerator;
use Farmero\worldsystem\generators\FlatGenerator;
use Farmero\worldsystem\generators\PMGenerator;

class WorldCommand extends Command {

    private $plugin;

    public function __construct(WorldSystem $plugin) {
        parent::__construct("world");
        $this->plugin = $plugin;
        $this->setDescription("Manage worlds");
        $this->setUsage("/world <create|delete|rename|list> [args]");
        $this->setPermission("worldsystem.cmd");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender->hasPermission("worldsystem.cmd")) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
            return false;
        }

        if (count($args) < 1) {
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }

        switch (strtolower($args[0])) {
            case "create":
                if (count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /world create <worldName> [generatorType]");
                    return false;
                }
                $worldName = $args[1];
                $generatorType = $args[2] ?? "normal";
                $this->createWorld($sender, $worldName, $generatorType);
                break;
            case "delete":
                if (count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /world delete <worldName>");
                    return false;
                }
                $worldName = $args[1];
                $this->deleteWorld($sender, $worldName);
                break;
            case "rename":
                if (count($args) < 3) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /world rename <oldName> <newName>");
                    return false;
                }
                $oldName = $args[1];
                $newName = $args[2];
                $this->renameWorld($sender, $oldName, $newName);
                break;
            case "list":
                $this->listWorlds($sender);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
                break;
        }

        return true;
    }

    private function createWorld(CommandSender $sender, string $worldName, string $generatorType): void {
        $server = $this->plugin->getServer();
        if ($server->getWorldManager()->isWorldGenerated($worldName)) {
            $sender->sendMessage(TextFormat::RED . "World '$worldName' already exists.");
            return;
        }

        switch (strtolower($generatorType)) {
            case "void":
                $generatorClass = VoidGenerator::class;
                break;
            case "flat":
                $generatorClass = FlatGenerator::class;
                break;
            case "pmgenerator":
            case "normal":
            default:
                $generatorClass = PMGenerator::class;
                break;
        }

        $server->getWorldManager()->generateWorld($worldName, WorldCreationOptions::create()->setGeneratorClass($generatorClass));
        $sender->sendMessage(TextFormat::GREEN . "World '$worldName' created successfully with generator '$generatorType'.");
    }

    private function deleteWorld(CommandSender $sender, string $worldName): void {
        $server = $this->plugin->getServer();
        $world = $server->getWorldManager()->getWorldByName($worldName);
        if ($world === null) {
            $sender->sendMessage(TextFormat::RED . "World '$worldName' does not exist.");
            return;
        }

        if ($server->getWorldManager()->unloadWorld($world)) {
            $worldPath = $server->getDataPath() . "worlds/" . $worldName;

            $this->deleteDirectory($worldPath);

            if (!is_dir($worldPath)) {
                $sender->sendMessage(TextFormat::GREEN . "World '$worldName' deleted successfully.");
            } else {
                $sender->sendMessage(TextFormat::RED . "Failed to delete world '$worldName'.");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "Failed to unload world '$worldName'.");
        }
    }

    private function deleteDirectory(string $dir): void {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }

    private function renameWorld(CommandSender $sender, string $oldName, string $newName): void {
        $server = $this->plugin->getServer();
        $world = $server->getWorldManager()->getWorldByName($oldName);
        if ($world === null) {
            $sender->sendMessage(TextFormat::RED . "World '$oldName' does not exist.");
            return;
        }

        if ($server->getWorldManager()->isWorldGenerated($newName)) {
            $sender->sendMessage(TextFormat::RED . "World '$newName' already exists.");
            return;
        }

        if ($server->getWorldManager()->unloadWorld($world)) {
            $oldPath = $server->getDataPath() . "worlds/" . $oldName;
            $newPath = $server->getDataPath() . "worlds/" . $newName;
            if (rename($oldPath, $newPath)) {
                $sender->sendMessage(TextFormat::GREEN . "World '$oldName' renamed to '$newName' successfully.");
            } else {
                $sender->sendMessage(TextFormat::RED . "Failed to rename world folder from '$oldName' to '$newName'.");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "Failed to unload world '$oldName'.");
        }
    }

    private function listWorlds(CommandSender $sender): void {
        $server = $this->plugin->getServer();
        $worlds = $server->getWorldManager()->getWorlds();
        $worldNames = array_map(fn($world) => $world->getFolderName(), $worlds);

        if (empty($worldNames)) {
            $sender->sendMessage(TextFormat::YELLOW . "There are no worlds available.");
        } else {
            $sender->sendMessage(TextFormat::GREEN . "Available worlds: " . implode(", ", $worldNames));
        }
    }
}