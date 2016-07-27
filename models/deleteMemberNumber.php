<?php
class deleteMemberNumber {
    function deleteNumber($db,$email,$id) {
        $sql = "DELETE FROM membersNumbers where memberEmail = :email AND mNumID = :id ";
        $sth = $db->prepare($sql);
        $sth->bindParam("email",$email);
        $sth->bindParam("id",$id);
        
        return $sth->execute();
    }
}

?>