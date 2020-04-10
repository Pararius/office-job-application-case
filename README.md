Repo that is used by the Casco Team to do job interviews.

In this assignment we are going to:
- Read a CSV file
- Publish the listings to Google PubSub
- Read from PubSub and write the data to Firestore

To install everything (assuming that you have docker and docker-compose):
`make install`

To run the tests:
`make test`

The code is already implemented. You can import data via:
`bin/console listing:import`

You can process data via:
`bin/console listing:process`

You can show all data in the system with:
`bin/console listing:all`

You do this from inside your docker container:

`docker-compose run --rm php sh`

This will automatically start a pubsub and a firestore emulator.
