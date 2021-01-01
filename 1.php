 <?php

Class ClassA { 

                 function bc($b, $c) { 

                  $bc = $b + $c; 

                  echo $bc; 

                 } 

            } 

$instance = new ClassA();

          call_user_func_array(array($instance,'bc'), array("111", "222")); 
