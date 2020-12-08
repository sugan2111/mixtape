# MixTape

## Requirements
- PHP 7+
- PHPUnit

## Running

```
 php run.php <input-data.json> <changes.json> <output-file-name.json>
```

example:
```
 php run.php src/data/input.json src/data/changes.json src/data/output.json
```

## Changes File
- As per requirements, 3 actions are supported 
- - Add Playlist
- - Add Songs to playlist
- - Remove playlist
- Every action will need a payload as shown below.

```
{
    "actions": [
        {
            "name": "ADD_PLAYLIST",
            "payload": {
                "user_id": "1",
                "song_ids": ["2", "3", "5"] 
            }
        },
        {
            "name": "ADD_SONGS_TO_PLAYLIST",
            "payload": {
                "playlist_id": "3",
                "song_ids": ["2"] 
            }
        },
        {
            "name": "REMOVE_PLAYLIST",
            "payload": {
                "playlist_id": "3"
            }
        }
    ]
}
```

## Testing

Install phpUnit via composer

`composer require --dev phpunit/phpunit ^8`

How to run PhpUnit tests

`./vendor/bin/phpunit --bootstrap vendor/autoload.php test/AppTest`

## Considerations

Before moving the application to production we need to consider the following:
1. Right now, there is no authentication/login feature involved. Any user can delete a playlist.
When deploying to production we need to authenticate a user if he is allowed to access and delete the playlist.
This should be implemented when taking security into consideration.


2. Logging needs to be added


3. Exceptions must be handled for a graceful exit

## Scaling Discussions

Application will need to be scaled for a number of reasons, the cases to be considered here are :
1) Handle very large input files
2) Handle very large changes files

 Approaches for coping with load
- When input data is very large, we cannot store data in memory as we are doing now. 
So it could be thought of to persist the data in a database.
  (Ex) Cassandra
 - Data can be sharded across multiple machines which theoretically allows for unlimited size
 
For multiple changes in changes file:

- If the order of the changes does not matter, the changes can be processed in parallel. This will ensure faster 
processing.
  

 - Based on the QPS of the system, the number of machines can be scaled up or down


- When considering scaling, stateless services can be distributed across multiple machines(scale out)
and stateful data systems such as database should be scaled up.