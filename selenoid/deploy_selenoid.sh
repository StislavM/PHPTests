#!/usr/bin/env bash

images=($(grep '"image": "' browsers/browsers.json |cut -d ':' -f 3 -f 2 | cut -d '"' -f 2 ))

#pull all required images
for img in "${images[@]}"
do
    docker pull -q $img
done

docker pull aerokube/selenoid:latest-release
docker pull aerokube/selenoid-ui:latest-release
docker pull selenoid/video-recorder:latest-release

#create .env file with video storage dir
abspass=$PWD
echo OVERRIDE_VIDEO_OUTPUT_DIR=${abspass%%S*d}logs/video/ > .env

# start selenoid
docker-compose up --build
