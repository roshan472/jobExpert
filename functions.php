<?php  
function connect(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db="job";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
     }
     return $conn;
    }

    
function getjobs(){
    $conn = connect();
    $j = $conn->query(
        "select * from jobs j 
        inner join category c on j.cat_id = c.cat_id
        inner join company cm on j.Cid = cm.Cid where j.jstatus=0");
        while($row = $j->fetch_assoc()){
            $getJob[] = $row;
         }
         if(isset($getJob)){
         return $getJob;  
        }
             
}


function getjobsbycategory($category){
    $conn = connect();
    $res = $conn->query("select * from jobs j 
    inner join category c on j.cat_id = c.cat_id
    inner join company cm on j.Cid = cm.Cid where c.cat_id='$category' and j.jstatus=0");
    $getJob=[];
    while($row = $res->fetch_assoc()){
        $getJob[] = $row;
    }
    if(isset($getJob)){
        return $getJob;  
       }      
 }


 function getCompanyDetails($cid){
    $conn = connect();
    $res = $conn->query("select * from company where Cid = $cid");
    $row = $res->fetch_assoc();
    return $row;   
 }

 function getconnectCompany($cid){
    $conn = connect();
    $j = $conn->query(
        "select jm.jsID,js.fname,js.lname from job_apply_c jc 
        inner join job_apply_m jm on jc.jobm_id=jm.jobm_id 
        inner join jobs j on j.job_id = jc.job_id
        inner join jobseeker js on jm.jsID = js.jsID
        where j.Cid = $cid and jc.selectStatus=1");
        $getJob=[];
        while($row = $j->fetch_assoc()){
            $getJob[] = $row;
         }
         return $getJob;  
 }

 function connectionMake($jsid,$cnid){
    $conn = connect();
    $j = $conn->query("select con_id from where jsID=$jsid");
    $conid = 0;
    if($j->num_rows > 0){
        $res = $j->fetch_assoc();
        $conid=$res['con_id'];
    }
    else{
        $conn->query("insert into connection(jsID) value($jsid)");
        $conid = mysqli_insert_id($conn);
    }

    $j = $conn->query("insert into connection_child(con_id,conneted_jsID,status) values($conid,$cnid,0)");

    $j = $conn->query("select con_id from where jsID=$cnid");
    $connid = 0;
    if($j->num_rows > 0){
        $res = $j->fetch_assoc();
        $connid=$res['con_id'];
    }
    else{
        $conn->query("insert into connection(jsID) value($cnid)");
        $connid = mysqli_insert_id($conn);
    }
    $j = $conn->query("insert into connection_child(con_id,conneted_jsID,status) values($connid,$jsid,-1)");
 }

?>