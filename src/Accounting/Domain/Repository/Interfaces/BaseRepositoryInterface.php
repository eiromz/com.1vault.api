<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface BaseRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function delete($id);
    public function create(array $details);
    public function update($id, array $newDetails);
    public function getDetailsByParams(array $details);
}
