<?php

/**
 * Interface DataInterface
 */
interface DataInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param $data
     * @return DataInterface
     */
    public function fromData($data): DataInterface;
}
