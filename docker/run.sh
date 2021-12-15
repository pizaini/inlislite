#!/bin/bash
if [ "$(ls -A /app/web/uploaded_files)" ]
  then
    echo "Uploaded_files exist..."
  else
    unzip /docker/uploaded_files.zip -d /app/web/uploaded_files/
fi

#start services
echo "Start services..."
apachectl -D FOREGROUND