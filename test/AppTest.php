<?php

use PHPUnit\Framework\TestCase;
require_once __dir__.'/../bootstrap.php';

/**
 * Class AppTest
 */
class AppTest extends TestCase
{

    /**
     * @var array
     */
    private $input = [
        "users" => [
            [
                "id" => 1,
                "name" => "Albin Jaye"
            ],
            [
                "id" => 2,
                "name" => "Dipika Crescentia"
            ]
        ],
        "songs" => [
            [
                "id" => "1",
                "artist" => "Camila Cabello",
                "title" => "Never Be the Same"
            ],
            [
                "id" => "2",
                "artist"=> "Zedd",
                "title"=> "The Middle"
            ],
            [
                "id" => "3",
                "artist"=> "The Weeknd",
                "title"=> "Pray For Me"
            ],
            [
                "id" => "4",
                "artist"=> "Drake",
                "title"=> "God's Plan"
            ],
            [
                "id" => "5",
                "artist"=> "Bebe Rexha",
                "title"=> "Meant to Be"
            ],
        ],
        "playlists" => [
            [
                "id" => "1",
                "user_id" => "1",
                "song_ids" => [
                     "1",
                     "2"
                ]
            ],
            [
                "id" => "2",
                "user_id" => "2",
                "song_ids" => [
                    "2"
                ]
            ],
            [
                "id" => "3",
                "user_id" => "1",
                "song_ids" => [
                    "4"
                ]
            ]
        ],
    ];

    // Test to check if a new playlist is added
    public function testAddPlayList() {
        $app = new App($this->input);
        $payload =  [
                "user_id" => "2",
                "song_ids"=> ["1", "3"]
            ];
        $app->performAction("ADD_PLAYLIST", $payload);
        $actual = $app->getOutput();
        $expected = [
            [
                "id" => "1",
                "user_id" => "1",
                "song_ids" => [
                    "1",
                    "2"
                ]
            ],
            [
                "id" => "2",
                "user_id" => "2",
                "song_ids" => [
                    "2",
                ]
            ],
            [
                "id" => "3",
                "user_id" => "1",
                "song_ids" => [
                    "4",
                ]
            ],
            [
                "id" => "4",
                "user_id" => "2",
                "song_ids" => [
                    "1",
                    "3"
                ]
            ]
        ];
        $this->assertCount(4, $actual['playlists']);
        $this->assertEquals($expected, $actual['playlists']);
    }

    // Test to check if a playlist is removed
    public function testRemovePlayList() {
        $app = new App($this->input);
        $payload =  [
            "playlist_id" => "3",
        ];
        $app->performAction("REMOVE_PLAYLIST", $payload);
        $actual = $app->getOutput();
        $expected = [
            [
                "id" => "1",
                "user_id" => "1",
                "song_ids" => [
                    "1",
                    "2"
                ]
            ],
            [
                "id" => "2",
                "user_id" => "2",
                "song_ids" => [
                    "2"
                ]
            ],
        ];
        $this->assertCount(2, $actual['playlists']);
        $this->assertEquals($expected, $actual['playlists']);
    }

    // Test to check if a song is added to playlist
    public function testSongPlayList() {
        $app = new App($this->input);
        $payload =  [
            "playlist_id" => "2",
            "song_ids" => [
                "4",
                "5"
            ]
        ];
        $app->performAction("ADD_SONGS_TO_PLAYLIST", $payload);
        $actual = $app->getOutput();
        $expected = [
            [
                "id" => "1",
                "user_id" => "1",
                "song_ids" => [
                    "1",
                    "2"
                ]
            ],
            [
                "id" => "2",
                "user_id" => "2",
                "song_ids" => [
                    "2",
                    "4",
                    "5"
                ]
            ],
            [
                "id" => "3",
                "user_id" => "1",
                "song_ids" => [
                    "4"
                ]
            ]
        ];
        $this->assertCount(3, $actual['playlists']);
        $this->assertEquals($expected, $actual['playlists']);
    }

    //Throw Exception to remove a playlist that does not exist
    public function testRemovePlayListThrowsExceptionForInvalidPlayList() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Requested playlistId 5 does not exist");
        $app = new App($this->input);
        $payload =  [
            "playlist_id" => "5",
        ];
        $app->performAction("REMOVE_PLAYLIST", $payload);
    }
}
