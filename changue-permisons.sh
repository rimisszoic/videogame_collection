#!/bin/bash
export DEPLOYPATH=/home/rmissgames/noelperez.rimisszoic.live/videogame_collection
# Cambiar permisos a las carpetas
chmod 0755 $DEPLOYPATH/config
chmod 0755 $DEPLOYPATH/controller
chmod 0755 $DEPLOYPATH/logs
chmod 0755 $DEPLOYPATH/model
chmod 0755 $DEPLOYPATH/resources
chmod 0755 $DEPLOYPATH/config/*.php
chmod 0755 $DEPLOYPATH/controller/*.php
chmod 0755 $DEPLOYPATH/model/*.php
chmod 0755 -R $DEPLOYPATH/resources/
