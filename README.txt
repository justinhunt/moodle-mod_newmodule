Template Activity Module for Moodle
==========================
This is a more modern, and at least for me, more useful template than the others available.
It contains admin and instance settings stubs, a renderer.php and a module.js . It also contains activity completion on grade, grade book logic, backup and restore and adgoc/scheduled tasks

To Use
===========
Replace all instances of MOD_NEWMODULE_ with your module name eg MOD_WIDGET_
Replace all instances of @@newmodule@@ with your module name eg widget
Replace all instances of COPYRIGHTNOTICE with something like "2015 Justin Hunt".
It is recommended to use a search and replace tool from a text editor or a command line script to do this.
Trying to do this manually is sure to lead to mistakes.
Make sure the plugin folder name, name of the langage file in lang/en, files in the backup/moodle2 folder, and files in the classes/task folder are also changed.

Copy the folder to your [Moodle Site Dir]/mod directory

Install as usual.

Enjoy.

Justin Hunt
poodllsupport@gmail.com