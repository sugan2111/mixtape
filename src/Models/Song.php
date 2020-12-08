<?php

/**
 * Class Song
 */
class Song implements DataInterface {
    /**
     * @var string
     */
    private $artist;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $id;

    /**
     * Song constructor.
     * @param string $id
     * @param string $artist
     * @param string $title
     */
    public function __construct(string $id, string $artist, string $title) {
        $this->id = $id;
        $this->artist = $artist;
        $this->title = $title;
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
    public function fromData($data) : DataInterface{
        return new Song(
            $data["id"],
            $data["artist"],
            $data["title"],
        );
    }

    /**
     * @return array
     */
    public function getData() : array {
        return [
            'id' => $this->id,
            'artist' => $this->artist,
            'title' => $this->title,
        ];
    }
}