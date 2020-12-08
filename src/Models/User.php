<?php

/**
 * Class User
 */
class User implements DataInterface {
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $id;

    /**
     * User constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name) {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getID() : string{
        return $this->id;
    }

    /**
     * @param $data
     * @return DataInterface
     */
    public function fromData($data) : DataInterface {
        return new User(
            $data["id"],
            $data["name"],
        );
    }

    /**
     * @return array
     */
    public function getData() : array{
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}