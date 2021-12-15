#!/bin/bash
if [ "$(ls -A /app/web/uploaded_files)" ]
  then
    echo "Uploaded_files exist..."
  else
    tar -xf /docker/uploaded_files.tar.gz -C /app/web/uploaded_files/
fi

#start services
echo "Start services..."
apachectl -D FOREGROUND