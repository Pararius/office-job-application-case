Repo that is used by the Casco Team to do job interviews.

In this assignment we are going to:
- Read a CSV file
- Publish the listings to Google PubSub
- Read from PubSub and write the data to Firestore

The code is already implemented, only not in a very object oriented way.


## Installation

To install everything (assuming that you have docker and docker-compose):

```sh
make install
```

This will build a php container, a [Firestore](https://cloud.google.com/firestore/docs/overview) 
emulator, and a [PubSub](https://cloud.google.com/pubsub/docs/overview) emulator.

## Usage

The application consists of 3 commands. You can run them inside the php container:

```sh
make up
docker-compose run --rm php sh
```

(This will automatically start the PubSub and Firestore emulator)

### Commands

Step 1: import data from the CSV file:

```sh
bin/console listings:import
```

Step 2: process the imported data (from PubSub):

```sh
bin/console listings:process
```

Step 3: show the processed data (from Firestore):

```sh
bin/console listings:show
```

You can also run all steps sequentially:

```
make run
```

## Testing

To run the tests:

```sh
make test
```

