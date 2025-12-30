<?php

namespace App\Utilities;

class SnowflakeGenerator
{
    private $epoch = 1672531200000; // Jan 1, 2023 in milliseconds
    private $machineId;
    private $lastTimestamp = -1;
    private $sequence = 0;

    public function __construct(int $machineId)
    {
        // Each server instance must be assigned a unique ID (0-1023)
        // via an environment variable or config.
        if ($machineId < 0 || $machineId > 1023) {
            throw new \InvalidArgumentException("Machine ID must be between 0 and 1023");
        }

        $this->machineId = $machineId;
    }

    public function nextId()
    {
        $timestamp = $this->timeGen();

        if ($timestamp === $this->lastTimestamp) {
            // Same millisecond? Increment sequence
            $this->sequence = ($this->sequence + 1) & 4095;
            if ($this->sequence === 0) {
                // Sequence overflow? Wait for next millisecond
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        // Bit-shifting logic to assemble the 64-bit ID
        return (($timestamp - $this->epoch) << 22)
            | ($this->machineId << 12)
            | $this->sequence;
    }

    protected function timeGen() {
        return (int) (microtime(true) * 1000);
    }

    protected function tilNextMillis($lastTimestamp) {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }
}
