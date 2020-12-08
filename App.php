<?php

/**
 * Class App
 */
class App {

    const ADD_PLAYLIST = "ADD_PLAYLIST";
    const REMOVE_PLAYLIST = "REMOVE_PLAYLIST";
    const ADD_SONGS_TO_PLAYLIST = "ADD_SONGS_TO_PLAYLIST";

    /**
     * @var mixed
     */
    private $user_collection;
    /**
     * @var mixed
     */
    private $playlist_collection;
    /**
     * @var mixed
     */
    private $song_collection;

    /**
     * App constructor.
     * @param $data
     */
    public function __construct($data) {
        $this->user_collection = $this->createCollectionsFromData(User::class, $data["users"]);
        $this->playlist_collection = $this->createCollectionsFromData(Playlist::class, $data["playlists"]);
        $this->song_collection = $this->createCollectionsFromData(Song::class, $data["songs"]);
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    private function createCollectionsFromData($model, $data) {
        return array_reduce($data, function($collection, $user_data) use($model) {
            $user = $model::fromData($user_data);
            $collection['Data'][$user->getID()] = $user;
            $collection['lastIdx'] = max($collection['lastIdx'], $user->getID());
            $collection['nextIdx'] = $collection['lastIdx'] + 1;
            return $collection;
        }, []);
    }

    /**
     * @param $action
     * @param $payload
     * @throws Exception
     */
    public function performAction($action, $payload) {
        switch($action) {
            case self::ADD_PLAYLIST:
                $this->addPlaylist($payload["user_id"], $payload["song_ids"]);
                break;
            case self::REMOVE_PLAYLIST:
                $this->removePlaylist($payload["playlist_id"]);
                break;
            case self::ADD_SONGS_TO_PLAYLIST:
                $this->addSongsToPlaylist($payload["playlist_id"], $payload["song_ids"]);
                break;
            default:
                throw new Exception("Action: $action not supported");
        }
    }

    /**
     * @param $user_id
     * @param $name
     * @param $song_ids
     * @throws Exception
     */
    private function addPlaylist($user_id, $song_ids) {
        if(count($song_ids) < 1) {
            throw new Exception("Playlist must contain atleast 1 song.");
        }

        // Validate if the given songId exist in the input list of songs.
        $songs = $this->getDataFromCollection($this->song_collection);
        foreach($song_ids as $songId) {
            if (!array_key_exists($songId,$songs)) {
                throw new Exception("Song with songId $songId does not exist");
            }
        }

        $this->playlist_collection['Data'][$this->playlist_collection['nextIdx']] = new Playlist(
            $this->playlist_collection['nextIdx'],
            $user_id,
            $song_ids,
        );
        $this->playlist_collection['lastIdx'] = $this->playlist_collection['nextIdx'];
        $this->playlist_collection['nextIdx']++;
    }

    /**
     * @param $playlist_id
     * @throws Exception
     */
    private function removePlaylist($playlist_id) {
        $playlist = $this->playlist_collection['Data'][$playlist_id];
        if($playlist == null)
            throw new Exception("Requested playlistId $playlist_id does not exist");
        unset($this->playlist_collection['Data'][$playlist_id]);
    }

    /**
     * @param $playlist_id
     * @param $song_ids
     * @throws Exception
     */
    private function addSongsToPlaylist($playlist_id, $song_ids) {
        $playlist = $this->playlist_collection['Data'][$playlist_id];
        if(empty($playlist))
            throw new Exception("Requested playlistId $playlist_id does not exist");
        $playlist->addSongs($song_ids);
    }

    /**
     * @param $collection
     * @return array
     */
    private function getDataAsListFromCollection($collection): array {
        return array_values($this->getDataFromCollection($collection));
    }

    /**
     * @param $collection
     * @return array
     */
    private function getDataFromCollection($collection): array {
        return array_map(function($model) {
            return $model->getData();
        }, $collection['Data']);
    }

    /**
     * @return array
     */
    public function getOutput() : array {
        return [
            "users" => $this->getDataAsListFromCollection($this->user_collection),
            "playlists" => $this->getDataAsListFromCollection($this->playlist_collection),
            "songs" => $this->getDataAsListFromCollection($this->song_collection),
        ];
    }

}