FROM google/cloud-sdk:alpine AS emulator

RUN apk add --update --no-cache openjdk8-jre
RUN gcloud components install cloud-firestore-emulator beta --quiet
RUN gcloud components install pubsub-emulator beta --quiet

FROM emulator AS firestore
ENTRYPOINT ["gcloud", "beta", "emulators", "firestore", "start", "--host-port=0.0.0.0:8081"]

FROM emulator AS pubsub
ENTRYPOINT gcloud beta emulators pubsub start --host-port=0.0.0.0:8082 --project="$PUBSUB_PROJECT_ID"
