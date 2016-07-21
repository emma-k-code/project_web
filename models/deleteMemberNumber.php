<?php
class deleteMemberNumber {
    function deleteNumber($db,$email,$id) {
        $result = $db->prepare("DELETE FROM membersNumbers where memberEmail = '$email' AND mNumID = '$id' ");
        return $result->execute();
    }
}

?>