<?php 
# Require the Important php
require_once "../../important.php";

# GET data action from URL
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

# Switch the case what action got from the URL to be performed in this controller module
switch($action){
    case 'logout':
        // destroy all sessions and cookies
        @session_destroy(); 
        setcookie("usr", "" ,time()-(60*60*24*7*30),"/", "","",TRUE);
        Page::redir("../index.php");
        break;

    case 'get_employee_name':
        getEmployeeName();
        break;
    
    case 'add_staff_ca':
        add_StaffCA();
        break;
    case 'add_staff_loan':
        add_StaffLoan();
        break;
    
    case 'add_staff_damages':
        add_StaffDamages();
        break;
        
    case 'listStaffCA':
        datatable_StaffCA();
        break;
    
    case 'listStaffLoan':
        datatable_StaffLoan();
        break;

    case 'listStaffDamages':
        datatable_StaffDamages();
        break;

    case 'listEmployeeMisc':

        break;
    
    case 'listPaidStaffCA':
        datatable_PaidStaffCA();
        break;

    case 'listPaidStaffLoan';
        datatable_PaidStaffLoan();
        break;

    case 'listPaidStaffDamages':
        datatable_PaidStaffDamages();
        break;

    case 'get_staff_ca':
        get_staffCA();
        break;
    
    case 'get_staff_loan':
        get_staffLoan();
        break;

    case 'get_staff_damages':
        get_staffDamages();
        break;
    
    case 'update_staff_ca':
        update_staffCA();
        break;
    
    case 'update_staff_loan':
        update_staffLoan();
        break;

    case 'update_staff_damages':
        update_staffDamages();
        break;
}

function getEmployeeName(){
    Employee::getDataName();
    return;
}

function add_StaffCA(){
    EmployeeCredits::add('staff_ca');
    return;
}

function add_StaffLoan(){
    EmployeeCredits::add('staff_loan');
    return;
}

function add_StaffDamages(){
    EmployeeCredits::add('staff_damages');
    return;
}

function datatable_StaffCA(){
    EmployeeCredits::fetchDataList('staff_ca');
    return;
}

function datatable_StaffLoan(){
    EmployeeCredits::fetchDataList('staff_loan');
    return;
}

function datatable_StaffDamages(){
    EmployeeCredits::fetchDataList('staff_damages');
    return;
}

function datatable_PaidStaffCA(){
    EmployeeCredits::fetchDataList('paid_staff_ca');
    return;
}

function datatable_PaidStaffLoan(){
    EmployeeCredits::fetchDataList('paid_staff_loan');
    return;
}

function datatable_PaidStaffDamages(){
    EmployeeCredits::fetchDataList('paid_staff_damages');
    return;
}

function get_staffCA(){
    EmployeeCredits::getData();
    return;
}

function get_staffLoan(){
    EmployeeCredits::getData();
    return;
}

function get_staffDamages(){
    EmployeeCredits::getData();
    return;
}

function update_staffCA(){
    EmployeeCredits::update('staff_ca');
    return;
}

function update_staffLoan(){
    EmployeeCredits::update('staff_loan');
    return;
}

function update_staffDamages(){
    EmployeeCredits::update('staff_damages');
    return;
}
?>