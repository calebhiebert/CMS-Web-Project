#!/bin/bash

docker stop pg-cms
docker rm pg-cms

docker build -t panchem/cms .

docker run -d \
        --link mysql \
        --name pg-cms \
        -e VIRTUAL_HOST="cms.piikl.com" \
        -e LETSENCRYPT_HOST="cms.piikl.com" \
        -e LETSENCRYPT_EMAIL="info@piikl.com" \
        panchem/cms

