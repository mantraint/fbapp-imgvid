<?php
    $callback = <<<EOT
    FB.ui({method: 'apprequests', message: 'Dummy message'});
EOT;
    $this->Js->domReady($callback);
?>