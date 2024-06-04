<?php

declare(strict_types=1);

namespace Farmero\worldsystem\generators;

use pocketmine\world\generator\Generator;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\biome\Biome;
use pocketmine\math\Vector3;

class FlatGenerator extends Generator {

    public function __construct(int $seed, string $preset) {
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);
        for ($x = 0; $x < 16; ++$x) {
            for ($z = 0; $z < 16; ++$z) {
                $chunk->setBiomeId($x, $z, Biome::PLAINS);
                for ($y = 0; $y < 64; ++$y) {
                    if ($y == 0) {
                        $chunk->setFullBlock($x, $y, $z, VanillaBlocks::BEDROCK()->getFullId());
                    } elseif ($y < 4) {
                        $chunk->setFullBlock($x, $y, $z, VanillaBlocks::DIRT()->getFullId());
                    } else {
                        $chunk->setFullBlock($x, $y, $z, VanillaBlocks::GRASS()->getFullId());
                    }
                }
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
    }

    public function getWorldSpawnPosition(ChunkManager $world): Vector3 {
        return new Vector3(0, 5, 0);
    }

    public function getName(): string {
        return "flat";
    }
}