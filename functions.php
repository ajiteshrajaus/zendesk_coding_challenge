<?php
/**
 * Created by PhpStorm.
 * User: ajitesh
 * Date: 4/09/16
 * Time: 9:32 PM
 */

function checkSearchableItems($file){
    $str = file_get_contents($file.'.json');
    $json = json_decode($str, true);
    $searchableKeys = array_keys($json[0]);
    return $searchableKeys;
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

function startSearch($objectToSearch){
    echo 'Enter the search item'."\n";
    $searchItemInputInput = commandLineInputHnadler();
    checkSearchableItems('users');
    if(in_array($searchItemInputInput,checkSearchableItems($objectToSearch))){
        echo 'Enter the search value'."\n";
        $searchValue = commandLineInputHnadler();
        $result=search($searchItemInputInput,$searchValue,$objectToSearch);
        if(count($result)>0){
            echo 'Found '.count($result). ' records matching the search criteria'."\n";
            displayResult($result);
            echo 'Press return to get back to the previous menu'."\n".'Press \'b\' to get back to main menu'."\n".'Type \'Quit\' to exit'."\n";
            menuInput(commandLineInputHnadler());
        }
        else{
            echo 'No records were found matching the search criteria'."\n";
            echo 'Press return to get back to the previous menu'."\n".'Press \'b\' to get back to main menu'."\n".'Type \'Quit\' to exit'."\n";
            menuInput(commandLineInputHnadler());
        }
    }
    else{
        echo 'Search item not present'."\n";
        echo 'Press return to get back to the previous menu'."\n".'Press \'b\' to get back to main menu'."\n".'Type \'Quit\' to exit'."\n";
        menuInput(commandLineInputHnadler());
    }

}

function menuInput($input){

    switch($input){
        case 1:
        case '':
            echo 'Select 1) Users 2) Tickets or 3) Organizations 4)Exit out quickly'."\n";
            $userInput = commandLineInputHnadler();
            switch($userInput){
                case 1:
                    startSearch('users');
                    break;
                case 2:
                    startSearch('tickets');
                    break;
                case 3:
                    startSearch('organizations');
                    break;
                case 4:
                    break;
                default: echo 'Incorrect input! Try again'."\n";
                menuInput('');
                break;
            }
            break;
        case 'b':
            startApplication();
            menuInput(commandLineInputHnadler());
            break;
        case 2:
            $userFields  = checkSearchableItems('users');
            $ticketsFields =checkSearchableItems('tickets');
            $organizationsFields = checkSearchableItems('organizations');
            displayFields($userFields,'users');
            displayFields($ticketsFields,'tickets');
            displayFields($organizationsFields,'organizations');
            echo 'Press \'b\' to get back to main menu'."\n".'Type \'Quit\' to exit'."\n";
            menuInput(commandLineInputHnadler());
            break;
        case (strtolower($input)=='quit'):
            break;
        default: echo 'nothing';
        break;


    }
}

function displayFields($fields,$object){
    echo '----------------------------------------------------------------'."\n"."\n";
    echo 'Search '.$object.' with following fields'."\n"."\n";
    foreach($fields as $field){
        echo $field."\n";
    }
    echo '----------------------------------------------------------------'."\n"."\n";
}

function commandLineInputHnadler(){
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    $line=str_replace(PHP_EOL, '', $line);
    return $line;
}

function startApplication(){
    echo 'Welcome to Zendesk Search'."\n".'Type \'quit\' to exit at any time, press \'Enter\' to continue'."\n\n\n\n";
    echo "\t".' Select search options:'."\n\t".'  * Press 1 to search Zendesk'."\n\t".'  * Press 2 to view list of searchable fields'."\n\t".'  * Type \'Quit\' to exit'."\n\n";
}

function displayResult($records){
    foreach($records as $record){
        echo "\n".'-----------------------------------------------------------------------------------------------------------------'."\n";
            foreach($record as $key => $value){
                if(is_array($value)){
                    foreach($value as $val){
                        echo "\n".$key.' '.$val."\n";
                    }
                }
                else{
                    echo "\n".$key.' => '.$value."\n";
                }
        }
        echo "\n".'-----------------------------------------------------------------------------------------------------------------------'."\n";
    }
}