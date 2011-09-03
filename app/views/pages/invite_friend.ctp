<?php
    $callback = <<<EOT
    FB.ui({method: 'apprequests', message: 'Dummy message'});
EOT;
    echo $this->Html->scriptStart();
    echo $this->Js->domReady($callback);
    echo $this->Html->scriptEnd();
?>