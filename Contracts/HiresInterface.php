<?php

namespace Modules\Tasks\Contracts;

interface HiresInterface
{
    public function getDatesAttribute();

    public function getMonthsAttribute();

    public function getYearsAttribute();

    public function task();

    public function user();
}
