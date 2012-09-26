<?php
namespace Domain\Repositories;
interface PostRepository extends Repository
{
    function getLatest($limit);
}