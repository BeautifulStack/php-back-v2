<?php

interface CrudInterface
{
    /**
     * Return all item in database
     *
     * args is the list of attributes you want to read
     * if empty, return all of them
     *
     * @param array $args
     * @return array
     */
    public function readAll(array $args): array;

    /**
     * Read attribute(s) of an item
     *
     * args[0]  : the item id you want to read from
     * args > 0 : is the list of attributes you want to read
     * if empty, return all of them
     *
     * @param array $args
     * @return array
     */
    public function read(array $args): array;

    /**
     * Create an item
     *
     * args is the list of attributes you want to fill
     * return error if empty or incomplete
     *
     * @param array $args
     */
    public function create(array $args);

    /**
     * Update attribute(s) of an item
     *
     * args[0]  : the item id you want to update from
     * args > 0 : is the list of attributes you want to update
     * return error if empty or incomplete
     *
     * @param array $args
     */
    public function update(array $args);

    /**
     * Delete item
     *
     * args is the list of item(s) id you want to delete
     * if empty, delete all of them (just kidding, it return an error)
     *
     * @param int $item
     */
    public function delete(int $item);
}