<?php

class MemberController extends Controller {
    
    function member() {
        $this->view("member", $user);
    }
    
}

?>