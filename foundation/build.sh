#!/bin/bash
target=../web/assets/vendor/foundation/
rm -r $target/* 2> /dev/null
cp _custom_settings.scss vendor/scss/. && cd vendor && sudo npm run build && cp -r {js,css} ../$target && echo "Successfully built libraries."