<?php

namespace DarkGhostHunter\Preloader;

use DateTime;

trait Conditions
{
    /**
     * If this Preloader should run
     *
     * @var bool
     */
    protected bool $shouldRun = true;

    /**
     * Run the Preloader scripts every number of hits in Opcache
     *
     * @param  int $hits
     * @return $this
     */
    public function whenEvery(int $hits = 50000) : self
    {
        return $this->when(fn () => $hits && $this->opcache->getHits() % $hits === 0);
    }

    /**
     * Run the Preloader script when Opcache hits reach certain number
     *
     * @param  int $hits
     * @return $this
     */
    public function whenHits(int $hits = 200000) : self
    {
        return $this->when(fn () => $hits === $this->opcache->getHits());
    }

    /**
     * Run the Preloader script one in a given chance
     *
     * @param  int $chances
     * @return $this
     */
    public function whenOneIn(int $chances = 100) : self
    {
        return $this->when(fn () => random_int(1, $chances) === (int)floor($chances/2));
    }

    /**
     * Run the Preloader script when the condition evaluates to true
     *
     * @param callable|bool $condition
     * @return $this
     */
    public function when($condition) : self
    {
        $this->shouldRun = (bool) (is_callable($condition) ? $condition() : $condition);

        return $this;
    }
}
