<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\DB;
class CommonHelper{

    public static function getTeamList($id){
       // echo"fff"; exit;
        $sql = "SELECT pm.manager_id,pm.developer_id,m.name AS manager_name, d.name AS developer_name FROM 
    project_managers pm 
JOIN  
    users m ON pm.manager_id = m.id AND m.role = '4' and pm.status = '1' and pm.project_id =$id 
JOIN 
    users d ON pm.developer_id = d.id AND d.role in (2,7) and pm.status = '1' and pm.project_id =$id ;
 ";
        $list = DB::select($sql);
        $manager = [];
        $developer = [];
         foreach($list as $record){
            $manager[] = $record->manager_name;
            $developer[] = $record->developer_name;
         }
         $finalManagerArray= array_unique($manager);
         $finalDeveloperArray= array_unique($developer);
         $string = "";
if(count($finalManagerArray) >0)
$string.= "Manager: ".implode(',',$finalManagerArray);
if(count($finalDeveloperArray) >0)
$string.= " \n Developer:".implode(',',$finalDeveloperArray);
        return $string;
    }
     
}