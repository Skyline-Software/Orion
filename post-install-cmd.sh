#!/bin/sh
if [ -n "$DYNO" ]  && [ -n "$YII_ENV" ]; then
    php init --env=$YII_ENV --overwrite=All
    php yii migrate/up --interactive=0
    php yii cache/flush-all
    php yii cache/flush-schema --interactive=0
fi
