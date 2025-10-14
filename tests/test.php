<?php
echo "mod_rewrite enabled: " . (in_array('mod_rewrite', apache_get_modules()) ? 'Yes' : 'No');
?>