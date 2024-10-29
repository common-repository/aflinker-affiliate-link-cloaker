
                  ======================================================================
                                   AFLinker Functionality Extension
                                         Help and Specification
                  ======================================================================

   AFLinker supports custom Functionality Extensions.
   Custom extensions allows people to add extra functionality to AFLinker, like custom filters, actions and functions.
   You may write any Wordpress-compatible plugin-like code, place it into '/extensions/YourExtension/main.php' file
   and it will be automatically invoked by AFLinker code upon initialization.

   Here are brief guidelines:

   Every AFLinker custom functionality extension writer must follow these guidelines
   -----------------------------------------------------------------------------------

      *  Include this file:  ./extensions/NameOfExtension/main.php   -  main executable code starting point of extension.
         This file, if present, will be invoked via include_once() PHP function by AFLinker at the time of initialization.
         This is a proper place to do any on-init type of functions. Feel free to include your custom actions and filters in it as well as any other files you need.
         See main.php file for the sample of custom filter code.

      *  Include this file:  ./extensions/NameOfExtension/admin.php  -  admin panel for this extension to appear inside of AFLinker admin settings.
         If your exptension does not need admin panel - just include empty admin.php, such as: <?php  ?>

      *  Include this file:  ./extensions/NameOfExtension/readme.txt -  information about your extension, how-to's, support and contact information.

   Testing this sample extension
   -----------------------------

      *  Rename the directory for this extension to anything that does not start with 'Sample_' string. This way it (main.php) will be automatically loaded
         during AFLinker plugin initialization time.


   For more information about AFLinker API please contact our development team at:
   http://www.AFLinker.com/contact/