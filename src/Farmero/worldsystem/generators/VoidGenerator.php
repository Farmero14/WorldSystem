<?php

declare(strict_types=1);

namespace Farmero\worldsystem\generators;

use pocketmine\world\generator\Generator;
use pocketmine\world\ChunkManager;
use pocketmine\world\SimpleChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\biome\Biome;
use pocketmine\world\generator\object\OreType;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;

class VoidGenerator extends Generator {

    public function __construct(int $seed, string $preset) {
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
    }

    public function getWorldSpawnPosition(ChunkManager $world): Vector3 {
        return new Vector3(0, 64, 0);
    }

    public function getName(): string {
        return "void";
    }
}