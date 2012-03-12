#!/usr/bin/env bash
APP="lectio"
echo "uploader $APP ..."

cd $(dirname $0)

if [ -f config.cfg ];then
  source config.cfg
fi

if [ -z "$SKYEN_USERNAME" ];then
  echo -n "Angiv dit brugernavn og tryk [ENTER]: "
  read SKYEN_USERNAME
  touch config.cfg
  echo "SKYEN_USERNAME=$(SKYEN_USERNAME)" >> config.cfg
  source config.cfg
fi

rsync -av $APP $SKYEN_USERNAME@skyen.iftek.dk:public_html
if [ "$?" -ne "0" ]; then
  echo "Der skete en fejl:("
  exit 1
fi

ssh $SKYEN_USERNAME@skyen.iftek.dk 'chmod 777 ~/public_html/lectio/db'

echo -e "\n$APP uploaded til http://skyen.iftek.dk/~$SKYEN_USERNAME/$APP"
sleep 5

