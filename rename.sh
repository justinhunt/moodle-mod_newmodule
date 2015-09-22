#!/bin/bash
# Shell script to rename mod_newtemplate into something new
# Author: poodll
printf "enter new module short name, ie if mod_XXX enter XXX \n" 
read MODULEENTRY
MODULENAME=$(tr '[:lower:]' '[:upper:]' <<< $MODULEENTRY)
LMODULENAME=$(tr '[:upper:]' '[:lower:]' <<< $MODULENAME)
find ./NEWMODULE -type f -exec sed -i "s|@@NEWMODULE@@|${MODULENAME}|g" {} \;
find ./NEWMODULE -type f -exec sed -i "s|MOD_NEWMODULE_|MOD_${MODULENAME}_|g" {} \;
find ./NEWMODULE -type f -exec sed -i "s|@@newmodule@@|${LMODULENAME}|g" {} \;
printf "enter the year, your name and email address on one line for the copyright notice. eg 2015 John Doe jdoe@email.com \n" 
read COPYRIGHTNOTICE
find ./NEWMODULE -type f -exec sed -i "s|COPYRIGHTNOTICE|${COPYRIGHTNOTICE}_|g" {} \; 
mv NEWMODULE/lang/en/NEWMODULE.php NEWMODULE/lang/en/${LMODULENAME}.php
mv NEWMODULE/backup/moodle2/backup_NEWMODULE_activity_task.class.php NEWMODULE/backup/moodle2/backup_${LMODULENAME}_activity_task.class.php
mv NEWMODULE/backup/moodle2/backup_NEWMODULE_stepslib.php NEWMODULE/backup/moodle2/backup_${LMODULENAME}_stepslib.php
mv NEWMODULE/backup/moodle2/restore_NEWMODULE_activity_task.class.php NEWMODULE/backup/moodle2/restore_${LMODULENAME}_activity_task.class.php
mv NEWMODULE/backup/moodle2/restore_NEWMODULE_stepslib.php NEWMODULE/backup/moodle2/restore_${LMODULENAME}_stepslib.php
mv NEWMODULE $LMODULENAME
echo "finished renaming NEWMODULE to ${LMODULENAME}"
