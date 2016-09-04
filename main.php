<?php
/**
 * Created by PhpStorm.
 * User: ajitesh
 * Date: 3/09/16
 * Time: 7:45 PM
 */

echo 'Welcome to Zendesk Search'."\n".'Type \'quit\' to exit at any time, press \'Enter\' to continue'."\n\n\n\n";

echo "\t".' Select search options:'."\n\t".'  * Press 1 to search Zendesk'."\n\t".'  * Press 2 to view list of searchable fields'."\n\t".'  * Type \'Quit\' to exit'."\n\n";

$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$line=str_replace(PHP_EOL, '', $line);

function checkSearchableItems($item){
    if($item == 'users'){
        $str = file_get_contents('users.json');
        $json = json_decode($str, true);
        $searchableKeys = array_keys($json[0]);
        return $searchableKeys;
    }
    elseif($item == 'organizations'){

    }
    elseif($item == 'tickets'){

    }
}

function search($searchItemInput,$searchValue,$objectToSearch){
        $str = file_get_contents($objectToSearch.'.json');
        $records = json_decode($str, true);
        $result = array();
        foreach($records as $record){
            if(is_array($record[$searchItemInput])){
                foreach($record[$searchItemInput] as $subrecord){
                    if($subrecord == $searchValue){
                        array_push($result,$record);
                    }
                }
            }
            else{
                if($record[$searchItemInput] == $searchValue){
                    array_push($result,$record);
                }
            }
        }
    return $result;
}

switch($line){
    case 1:
        echo 'Select 1) Users 2) Tickets or 3) Organizations'."\n";
        $userInput = fgets($handle);
        $userInput=str_replace(PHP_EOL, '', $userInput);
        switch($userInput){
            case 1:
                echo 'Enter the search item'."\n";
                $searchItemInput = fgets($handle);
                $searchItemInput=str_replace(PHP_EOL, '', $searchItemInput);
                checkSearchableItems('users');
                if(in_array($searchItemInput,checkSearchableItems('users'))){
                    echo 'Enter the search value'."\n";
                    $searchValue = fgets($handle);
                    $searchValue=str_replace(PHP_EOL, '', $searchValue);
                    $result=search($searchItemInput,$searchValue,'users');
                    if(count($result)>0){
                        print_r($result);
                    }
                    else{
                        echo 'No results found';
                    }
                }
                else{
                    echo 'Search item not present';
                }

        }
        break;
    case 2:
        echo 'Its 2';
        break;
    case (strtolower($line)=='quit'):
        echo 'Its Quit';
        break;
    default: echo 'nothing';

}
