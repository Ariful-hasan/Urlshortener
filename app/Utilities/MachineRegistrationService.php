<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Redis;

class MachineRegistrationService
{
    protected $myId;

    public function register()
    {
        // Atomically pop a random ID from the set
        $this->myId = Redis::spop('available_machine_ids');

        if (!$this->myId) {
            throw new \Exception("No Machine IDs available! Cluster is too large.");
        }

        // Store it in a local property for the Snowflake generator to use
        return (int) $this->myId;
    }

    public function __destruct()
    {
        // When the PHP process ends (if handled gracefully), put the ID back
        if ($this->myId !== null) {
            Redis::sadd('available_machine_ids', $this->myId);
        }
    }
}
