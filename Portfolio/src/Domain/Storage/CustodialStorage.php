<?php

namespace App\Domain\Storage;

/*
 * A custodial storage is a place where you own assets
 * Those assets are under local control of a third party
 * They may be stolen or frozen, don't forget this
 * */
interface CustodialStorage extends Storage
{
    public function getCustodian(): string;
    public function hasRestApi(): bool;
    public function hasWsApi(): bool;
}
