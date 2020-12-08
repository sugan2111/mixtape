<?php

/**
 * Class Playlist
 */
class Playlist implements DataInterface {
    /**
     * @var array
     */
    private $songIds;
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $id;

    /**
     * Playlist constructor.
     * @param string $id
     * @param string $userId
     * @param array $songIds
     */
    public function __construct(string $id, string $userId, array $songIds) {
        $this->id = $id;
        $this->userId = $userId;
        $this->songIds = array_unique($songIds);
    }

    /**
     * @return string
     */
    public function getID() : string {
        return $this->id;
    }

    /**
     * @param $data
     * @return DataInterface
     */
    public function fromData($data) : DataInterface{
        return new Playlist(
            $data["id"],
            $data["user_id"],
            $data["song_ids"],
        );
    }

    /**
     * @param $songIds
     */
    public function addSongs($songIds) {
        $this->songIds = array_unique(array_merge($this->songIds, $songIds));
        $this->songIds = array_values($this->songIds);
    }

    /**
     * @return array
     */
    public function getData() : array{
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'song_ids' => $this->songIds,
        ];
    }
}