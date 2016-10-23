#!/bin/bash
(sudo echo "") && (composer install --no-dev --optimize-autoloader) && (php bin/console cache:clear --env=prod --no-debug) && (php bin/console assetic:dump --env=prod --no-debug) && (sudo chown -R root /srv; sudo chgrp -R web /srv; sudo chmod -R 770 /srv;)
