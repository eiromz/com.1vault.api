<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface BaseRepositoryInterface
{
    public function setUser($user);

    public function getAll();

    public function owner($user);

    public function member($user);

    public function getById($id);

    public function delete($id);

    public function create(array $details);

    public function update($id, array $newDetails);

    public function getDetailsByParams(array $details);

    public function getAllByParams(array $details);
}
