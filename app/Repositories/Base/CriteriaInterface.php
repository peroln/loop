<?php

declare(strict_types=1);

namespace App\Repositories\Base;

use Illuminate\Support\Collection;

interface CriteriaInterface extends RepositoryInterface
{
    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true): self;

    /**
     * @return mixed
     */
    public function getCriteria(): Collection;

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria): self;

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria): self;

    /**
     * @return $this
     */
    public function applyCriteria(): self;
}
