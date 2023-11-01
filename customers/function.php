<?php
   require '../inc/dbcon.php';

function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entry");
    echo json_encode($data);
    exit();
}

function storeCustomer($customerInput){
    global $conn;

    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $country = mysqli_real_escape_string($conn, $customerInput['country']);

    if(empty(trim($name))){

        return error422('enter your name');

    }elseif(empty(trim($email))){

        return error422('enter your email');
    }elseif(empty(trim($country))){

        return error422('enter your country');
    }
    else{
        $query = "INSERT INTO forpractice (username, email, country) VALUES('$name', '$email', $country) ";
        // INSERT INTO `forpractice`(`sno`, `username`, `email`, `country`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]')
        $result = mysqli_query($conn, $query);

        if($result){
            $data =[
                'status' => 201,
                'message' => 'customer created successfully',
           ];
           header("HTTP/1.0 201 created");
           return json_encode($data);

        }else{
            $data =[
                'status' => 500,
                'message' => 'internal server error',
           ];
           header("HTTP/1.0 500 internal server error");
           return json_encode($data);

        }
    }

}

function getCustomerlist(){

global $conn;

$requestMethod = $_SERVER["REQUEST_METHOD"];
$query = "SELECT * FROM forpractice";
$query_run = mysqli_query($conn, $query);

if($query_run){
    if(mysqli_num_rows($query_run) > 0){
        $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
        $data =[
            'status' => 200,
            'message' => 'customer list fetched successfully',
            'data' => $res
        ];
        header("HTTP/1.0 200 ok");
        return json_encode($data);

    }else{
        $data =[
            'status' => 404,
            'message' => 'No record found',
            
        ];
        header("HTTP/1.0 404 No record found");
        return json_encode($data);
    }

} 
else
{
    $data =[
        'status' => 500,
        'message' => 'internal server error',
    ];
    header("HTTP/1.0 500 internal server error");
    return json_encode($data);
}


}
?>