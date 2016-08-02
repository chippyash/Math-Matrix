#!/bin/bash
#Create test contract
cd ~/Projects/chippyash/source/Math-Matrix
vendor/phpunit/phpunit/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Math Matrix" contract.html docs/Test-Contract.md
rm contract.html

#Create API Documentation
if [ ! -d "../../apidoc/Math-Matrix" ]
then
	mkdir ../../apidoc/Math-Matrix
fi
apigen generate -s "src/Chippyash/Math/Matrix/" -d "../../apidoc/Math-Matrix" --title "Chippyash Math Matrix" --php --access-levels "public"