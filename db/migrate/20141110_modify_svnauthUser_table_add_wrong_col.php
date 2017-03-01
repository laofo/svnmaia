<?php
        $query="alter table svnauth_user add wrongs int(9) Default 0";
        mysql_query($query);
        $query="alter table svnauth_user add lastErrorTime TIMESTAMP ";
        mysql_query($query);
        $mysql_err.= mysql_error();
