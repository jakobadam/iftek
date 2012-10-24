#!/usr/bin/env bash
APP="blog"
echo "uploader $APP ..."

cd $(dirname $0)

if [ -f config.cfg ];then
  source config.cfg
fi

if [ -z "$SKYEN_USERNAME" ];then
  echo -n "Angiv dit brugernavn og tryk [ENTER]: "
  read username
  touch config.cfg
  echo "SKYEN_USERNAME=$username" >> config.cfg
  source config.cfg
fi

rsync -av blog $SKYEN_USERNAME@skyen.iftek.dk:public_html
echo -e "\n$APP uploaded til http://skyen.iftek.dk/~$SKYEN_USERNAME/$APP"
sleep 5

