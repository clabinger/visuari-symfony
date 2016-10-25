#!/bin/bash
target=../web/assets/vendor/foundation/
rm -r $target/* 2> /dev/null
cp _custom_settings.scss vendor/scss/. && cd vendor && sudo npm run build && cp -r {js,css} ../$target && (sudo chown -R root /srv; sudo chgrp -R web /srv; sudo chmod -R 770 /srv;) && echo "Successfully built libraries."
