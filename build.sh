#!/bin/bash
cd ~/Projects/chippyash/source/Math-Matrix
vendor/phpunit/phpunit/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Math Matrix" contract.html docs/Test-Contract.md
rm contract.html

