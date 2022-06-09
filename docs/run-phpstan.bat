@echo off

cls

set Level=9

echo -------------------------------------------------------
echo RUNNING PHPSTAN ANALYSIS @ %Level%
echo -------------------------------------------------------

echo.

call ../vendor/bin/phpstan analyse -c ./config/phpstan.neon -l %Level% > phpstan/output.txt

start "" "phpstan/output.txt"
