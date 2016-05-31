#!/bin/bash

TARGET_NAME="PRODUCTION"

SOURCE_SERVER="spsy.eu"
SOURCE_DIR="/var/www/joga.spsy.eu/web/"

TARGET_SERVER=""
TARGET_DIR="../"

LOG=".log/rsync_download.log"

rsync -rvclk $SOURCE_SERVER:$SOURCE_DIR $TARGET_SERVER$TARGET_DIR --delete-after --dry-run --exclude-from=.deploy_ignore --log-file=$LOG > rsync.diff
cat rsync.diff

echo
echo "YOU'RE TRYING TO UPDATE $TARGET_NAME SERVER"
echo
echo "Do you right to upload these files ?"
select yn in "Yes" "No"; do
    case $yn in
        Yes ) rsync -rvclk $SOURCE_SERVER:$SOURCE_DIR $TARGET_SERVER$TARGET_DIR --delete-after --exclude-from=.deploy_ignore --log-file=$LOG;
              cat rsync.diff
              break;;
        No ) exit;;
    esac
done

