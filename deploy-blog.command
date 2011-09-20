#!/usr/bin/env bash
APP="blog"
echo "uploader $APP ..."

cd $(dirname $0)

if [ -f config.cfg ];then
  source config.cfg
fi

if [ -z "$USER" ];then
  echo -n "Angiv dit brugernavn og tryk [ENTER]: "
  read username
  touch config.cfg
  echo "USER=$username" >> config.cfg
  source config.cfg
fi

rsync -av blog $USER@skyen.iftek.dk:public_html
echo -e "\n$APP uploaded til http://skyen.iftek.dk/~$USER/$APP"
sleep 5

