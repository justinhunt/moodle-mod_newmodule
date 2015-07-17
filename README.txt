Template Activity Module for Moodle
==========================
This is a more modern, and at least for me, more useful template than the others available.
It contains admin and instance settings stubs, a renderer.php and a module.js . It also contains activity completion on grade, grade book logic, backup and restore and adgoc/scheduled tasks

To Use
===========
i) Replace all instances of @@NEWMODULE@@ with your uppercase module name eg WIDGET
There are 3 of these.

ii) Replace all instances of MOD_NEWMODULE_ with your module frankenstyle component name eg MOD_WIDGET_
(NB note the trailing underscore. Just to be safe, include that.)
There are 141 of these.

iii) Replace all instances of @@newmodule@@ with your lowercase module name eg widget
There are 331 of these.

iv) Replace all instances of COPYRIGHTNOTICE with something like "2015 Justin Hunt".
There are 31 of these.

It is recommended to use a search and replace tool from a text editor or a command line script to do this.
Trying to do this manually is sure to lead to mistakes.

Make sure the plugin folder name, and the names of the files in these directories are also changed:
lang/en
backup/moodle2
classes/task

Copy the folder to your [Moodle Site Dir]/mod directory

Install as usual.

Enjoy.

Justin Hunt
poodllsupport@gmail.com

NB
By default the newtemplate supports grading, but since there is nothing to grade ... yet ... when you do update a gradable item, you will need to call: [modulename]_update_grades($moduleinstance, $userid_of_student);
