<?php

/* **************************************************************************
This software is provided "as is" without any express or implied warranties,
including, but not limited to, the implied warranties of merchantibility and
fitness for any purpose.
In no event shall the copyright owner, website owner or contributors be liable
for any direct, indirect, incidental, special, exemplary, or consequential
damages (including, but not limited to, procurement of substitute goods or services;
loss of use, data, rankings with any search engines, any penalties for usage of
this software or loss of profits; or business interruption) however caused and
on any theory of liability, whether in contract, strict liability, or
tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.
To request source code for AFLinker please contact http://www.aflinker.com/contact
************************************************************************** */

//===========================================================================
// Load custom AFLinker extensions

   $AF_ext_dirname = @dirname(__FILE__) . '/extensions';
   $AF_ext_dirname = str_replace ('\\', '/', $AF_ext_dirname);
   $AF_dh = @opendir ($AF_ext_dirname);
   if ($AF_dh)
      {
      while (($AF_found_file = readdir($AF_dh)) !== FALSE)
         {
         // Load main.cpp from any extension which directory is not named like 'Sample_'.
         if (is_dir($AF_ext_dirname . "/$AF_found_file") && $AF_found_file[0] != '.' && strcmp('YourExtensionSample', $AF_found_file) && strncmp ('Sample_', $AF_found_file, 7) && file_exists ($AF_ext_dirname . "/$AF_found_file/main.php"))
            {
            include_once ($AF_ext_dirname . "/$AF_found_file/main.php");
            }
         }
      closedir($AF_dh);
      }
//===========================================================================

?>