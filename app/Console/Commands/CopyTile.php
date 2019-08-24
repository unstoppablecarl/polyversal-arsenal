<?php

namespace App\Console\Commands;

use App\Models\Tile;
use App\Models\User;
use App\Services\TileService;
use Illuminate\Console\Command;

class CopyTile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy-tile
            {--tile-id= : Id of tile to copy}
            {--user-id= : user Id to copy tile to} 
';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy Tile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tileId = $this->option('tile-id');
        $userId = $this->option('user-id');

        /** @var TileService $service */
        $service = app(TileService::class);

        $tile = Tile::find($tileId);
        $user = User::find($userId);

        $service->copy($tile, $user->id);
    }
}
