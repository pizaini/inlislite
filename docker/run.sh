#!/bin/bash
if [ "$(ls -A /app/web/uploaded_files)" ]
  then
    echo "Uploaded_files exist..."
  else
    cp -r /app/docker/uploaded_files/* /app/web/uploaded_files/
fi

#change directory owner
echo "Change directory owner..."
chown www-data:www-data -R /app/web

#start services
echo "Start services..."
apachectl -D FOREGROUND