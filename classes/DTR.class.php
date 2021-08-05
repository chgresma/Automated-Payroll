<?php 

class DTR{
    private static $tbl_dtr = 'tbl_dtr';

    public static function addInitialDTR(){
        $query = Db::fetch('tbl_employees', '', '', '', '', '', '');
        $query4 = Db::fetch('tbl_overtime', '', '', '', '', '', '');
        $overtime_result = Db::num($query4);

        if(isset($_POST['start_date'])){
            $date = $_POST['start_date'];
            while($employee = Db::assoc($query)){
                if($employee['employee_status'] == 'active'){
                    $query2 = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? AND start_date = ?', array($employee['employee_number'], $date), '','','');
                    $query3 = Db::fetch('tbl_shifting_hours s JOIN tbl_employees e ON s.shifting_type_name = e.shifting_type_name', '', 'employee_number = ?', $employee['employee_number'], '', '', '');

                    $employee_shifting_result = Db::num($query3);

                    if(Db::count($query2) == 0){
                        Db::insert(self::$tbl_dtr, array('employee_id', 'employee_name', 'regular_hrs', 'regular_ot_hrs', 'start_date',  'time_in', 'end_date', 'time_out', 'ot_start_date', 'over_time_in', 'ot_end_date', 'over_time_out'), array($employee['employee_number'], $employee['first_name'].' '.$employee['last_name'], $employee_shifting_result[5], $overtime_result[2], $date, '', '', '', '', '', '' ,''));
                    }
                }
            }
        }
    }

    public static function addRecordWithScanner(){
        if(isset($_POST['card_id'])){
            $card_id        = $_POST['card_id'];
            $query_employee = Db::fetch('tbl_employees', '', 'card_id = ?', $card_id, '', '', '');
            $employee = Db::assoc($query_employee);

            $employee_id        = $employee['employee_number'];
            $employee_name      = $employee['first_name'].' '.$employee['last_name'];
            $date               = $_POST['date'];
            $time_in            = $_POST['time'];
            $time_out           = $_POST['time'];
            $over_time_in       = $_POST['time'];
            $over_time_out      = $_POST['time'];
            $total_work_hours   = 0;

            $query = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? AND date = ?', array($employee_id, $date), '', '','');
            
            if(Db::count($query) == 0){
                Db::insert(self::$tbl_dtr, array('employee_id', 'employee_name', 'date',  'time_in', 'time_out', 'over_time_in', 'over_time_out', 'total_work_hours'), array($employee_id, $employee_name, $date, $time_in, $time_out, $over_time_in, $over_time_out, $total_work_hours));
            }

            $result = Db::assoc($query);
            if($result['time_in'] == ''){
                if($time_in != ''){
                    Db::update(self::$tbl_dtr, array('time_in'), array($time_in), 'employee_id = ? AND date = ?', array($employee_id, $date));
                }
            }
            if($result['time_in'] != '' && $result['time_out'] == ''){
                if($time_out != ''){
                    Db::update(self::$tbl_dtr, array('time_out'), array($time_out), 'employee_id = ? and date = ?', array($employee_id, $date));
                }
            }
            if($result['time_out'] != '' && $result['over_time_in'] == ''){
                if ($over_time_in != ''){
                    Db::update(self::$tbl_dtr, array('over_time_in'), array($over_time_in), 'employee_id = ? and date = ?', array($employee_id, $date));
                }
            }
            if($result['over_time_in'] != '' && $result['over_time_out'] == ''){
                if($over_time_out != ''){
                    Db::update(self::$tbl_dtr, array('over_time_out'), array($over_time_out), 'employee_id = ? and date = ?', array($employee_id, $date));
                }
            }
        }
    }

    public static function addRecordNoScanner(){
        if(isset($_POST['employee_id'])){
            $employee_id        = $_POST['employee_id'];
            $employee_name      = $_POST['employee_name'];
            $date               = $_POST['date'];
            $time_in            = $_POST['time_in'];
            $time_out           = $_POST['time_out'];
            $over_time_in       = $_POST['over_time_in'];
            $over_time_out      = $_POST['over_time_out'];

            $query = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? AND start_date = ?', array($employee_id, $date), '', '','');

            if(Db::count($query) == 0){
                Db::insert(self::$tbl_dtr, array('employee_id', 'employee_name', 'start_date',  'time_in', 'end_date', 'time_out', 'over_time_in', 'over_time_out', ), array($employee_id, $employee_name, $date, $time_in, $time_out, $over_time_in, $over_time_out));
            } 

            $result = Db::assoc($query);

            if($result['time_in'] == ''){
                if($time_in != ''){
                    Db::update(self::$tbl_dtr, array('time_in'), array($time_in), 'employee_id = ? AND start_date = ?', array($employee_id, $date));
                }
            }
            if($result['time_in'] != '' && $result['time_out'] == ''){
                if($time_out != ''){
                    $time_out         = $_POST['time_out'];
                    Db::update(self::$tbl_dtr, array('end_date', 'time_out'), array($date, $time_out), 'employee_id = ? and start_date = ?', array($employee_id, $date));
                }
            }

            if($result['time_out'] != '' && $result['over_time_in'] == ''){
                if ($over_time_in != ''){
                    Db::update(self::$tbl_dtr, array('ot_start_date', 'over_time_in'), array($date ,$over_time_in), 'employee_id = ? and end_date = ?', array($employee_id, $date));
                }
            }
            if($result['over_time_in'] != '' && $result['over_time_out'] == ''){
                if($over_time_out != ''){
                    Db::update(self::$tbl_dtr, array('ot_end_date', 'over_time_out'), array($date,$over_time_out), 'employee_id = ? and ot_start_date = ?', array($employee_id, $date));
                }
            }
        }
    }

    public static function getDTRData(){
        if(isset($_GET['card_id'])){
            $date = $_GET['date'];
            $query = Db::fetch('tbl_employees', 'card_id = ?', '', '', '', '', '');
            $employee = Db::assoc($query);
            
            $query2 = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? AND date = ?', array($employee['employee_number'], $date), '', '', '');
            echo json_encode(Db::num($query2));
        }

        if(isset($_GET['employee_id'])){
            $query = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? AND date = ?', array($_GET['employee_id'], $_GET['date']), '', '', '');
            $query2 = Db::fetch('tbl_shifting_hours s JOIN tbl_employees e ON s.shifting_type_name = e.shifting_type_name', '', 'employee_number = ?', $_GET['employee_id'], '', '', '');
            $result = Db::num($query);
            $result2 = Db::num($query2);
            echo json_encode(array_merge($result, $result2));
        }

        if(isset($_GET['dtr_id'])){
            $query = Db::fetch(self::$tbl_dtr, '', 'id = ?', $_GET['dtr_id'], '', '', '');
            $result = Db::num($query);
            echo json_encode($result);
        }
    }
    
    public static function fetchEmployeeRecordDTRData(){
        if(!empty($_GET['search']['value'])){
            $like_val = $_GET['search']['value'];
            $query = Db::fetch(self::$tbl_dtr, '', 'employee_id = ? OR employee_name = ? OR date = ?', array($like_val, $like_val, $like_val), '', '', '');

            $list_data = array();
            while($tbl_DTR = Db::assoc($query)){

                $dataRow = array();
                $dataRow[] = date('M d, Y (D)', strtotime(date($tbl_DTR['date'])));
                $dataRow[] = $tbl_DTR['employee_id'];
                $dataRow[] = $tbl_DTR['employee_name'];
                $dataRow[] = $tbl_DTR['time_in'];
                $dataRow[] = $tbl_DTR['time_out'];
                $dataRow[] = $tbl_DTR['over_time_in'];
                $dataRow[] = $tbl_DTR['over_time_out'];
                $dataRow[] = $tbl_DTR['total_work_hours'];
                $dataRow[] = '<button type="button" name="update" id="'.$tbl_DTR['id'].'" class="btn btn-success update"><i class="material-icons">edit</i></button>';
                $list_data[] = $dataRow;
            }
            $query2 = Db::fetch(self::$tbl_dtr, '', '', '', '', '', '');
            $numRows = Db::count($query2);
            $result_data = array(
                'draw'              => intval($_GET['draw']),
                'recordsTotal'      => $numRows, 
                'recordsFiltered'   => $numRows,
                'data'              => $list_data
            );
            echo json_encode($result_data);
        } else {
                $dataRow = array();
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $dataRow[] = '';
                $list_data[] = $dataRow;

                $result_data = array(
                    'draw'              => intval($_GET['draw']),
                    'recordsTotal'      => 0, 
                    'recordsFiltered'   => 0,
                    'data'              => $list_data
                );
                echo json_encode($result_data);
        }
    }

    public static function fetchRecordDTRData(){
        $date_now = date('Y-m-d');
        $query = Db::fetch(self::$tbl_dtr, '', 'start_date = ?', $date_now, 'id DESC', '', '');
        $limit = $_GET['start'].', '.$_GET['length'];
        if($_GET['length'] != -1){
            $query = Db::fetch(self::$tbl_dtr, '','date = ?', $date_now,'id DESC', $limit, '');
        }
        if(!empty($_GET['search']['value'])){
            $like_val = '%'.$_GET['search']['value'].'%';
            $query = Db::fetch(self::$tbl_dtr, '', 'employee_id LIKE ? OR employee_name LIKE ? AND date = ?', array($like_val, $like_val, $date_now), '', '', '');
        }
        $list_data = array();
        while($tbl_DTR = Db::assoc($query)){
            $shifting_query = Db::fetch('tbl_shifting_hours s JOIN tbl_employees e ON s.shifting_type_name = e.shifting_type_name', '', 'employee_number = ?', $tbl_DTR['employee_id'], '', '', '');
            $result = Db::num($shifting_query);

            $dataRow = array();
            $dataRow[] = date('M d, Y', strtotime(date($tbl_DTR['start_date'])));
            $dataRow[] = $tbl_DTR['employee_id'];
            $dataRow[] = $tbl_DTR['employee_name'];
            $dataRow[] = explode(' ', $result[1])[0];
            $dataRow[] = (!empty($tbl_DTR['time_in'])) ? date('h:i a', strtotime($tbl_DTR['time_in'])) : '';
            $dataRow[] = (!empty($tbl_DTR['time_out'])) ? date('h:i a', strtotime($tbl_DTR['time_out'])) : '';
            $dataRow[] = $result[5];
            $dataRow[] = (!empty($tbl_DTR['over_time_in'])) ? date('h:i a', strtotime($tbl_DTR['over_time_in'])) : '';
            $dataRow[] = (!empty($tbl_DTR['over_time_out'])) ? date('h:i a', strtotime($tbl_DTR['over_time_out'])) : '';
            $dataRow[] = $tbl_DTR['total_work_hours'];
            $list_data[] = $dataRow;
        }
        $query2 = Db::fetch(self::$tbl_dtr, '', '', '', '', '', '');
        $numRows = Db::count($query2);
        $result_data = array(
            'draw'              => intval($_GET['draw']),
            'recordsTotal'      => $numRows, 
            'recordsFiltered'   => $numRows,
            'data'              => $list_data
        );
        echo json_encode($result_data);
    }

    public static function updateRecord(){
        
    }

    public static function removeRecord(){
        
    }
}