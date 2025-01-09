<?php

namespace App\Http\Helper;
use App\Http\Helper\SendMail;


class TimesheetMail{

    public static function timesheetMail($empName, $selectData, $times, $statusCode, $description, $data, $emailData)
    {
      $from = 'noreply@mybluethink.in';
      $cc = '';
      $to = $data->email;

      $name = str_replace("{{##USERNAME$}}",$empName,$emailData->content);
      $managerName = str_replace("{{##MANAGERNAME$}}",$data->name,$name);
      $projectName = str_replace("{{##PROJECTNAME$}}",$data->project_name,$managerName);
      $date = str_replace("{{##DATE$}}",$selectData,$projectName);
      $time = str_replace("{{##TIME$}}",$times,$date);
      $status = str_replace("{{##STATUS$}}",$statusCode,$time);
      $html = str_replace("{{##DESCRIPTION$}}",$description,$status);
       
      SendMail::sendMail($html, $emailData->subject, $to, $from, $cc);
    }

}